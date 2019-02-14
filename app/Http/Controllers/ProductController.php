<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\UOM;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\Percentage;
use App\Models\Tax;
use App\Models\Product;
use Validator;
use Response;
use View;
use Auth;
use Excel;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $rules =
    [
      'barcode_code'             => 'required|numeric|digits_between:10,15',
      'product_name'             => 'required',
      'product_category'         => 'required|min:1|max:32|numeric',
      'uom'                      => 'required|min:1|max:32|numeric',
      'currency'                 => 'required|min:1|max:32|numeric',
      'payment'                  => 'required|min:1|max:32',
      'purchase_price'           => 'numeric|required',
      'sales_price'              => 'numeric|required',
      'total_sales_price'        => 'numeric|required'
    ];

    public function index()
    {
          if(access_level_user('view','product')=='allow')
          {
              $category = Category::all();
              $uom      = UOM::all();
              $currency = Currency::all();
              activity_log(get_module_id('product'), 'View', '', '');
              return view('master/product/index',compact('category','uom','currency'));
          }
          else
          {
              activity_log(get_module_id('product'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }
    }

    public function list_data(Request $request)
    {
            $page     = $request->page;
            $per_page = $request->show_data;
            $output   = $request->output;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = Product::with('category','uom')
                                  ->when($request->category, function($query) use ($request)
                                  {
                                            return $query->where('product_cat_id','=',$request->category);
                                  })
                                  ->when($request->uom, function($query) use ($request)
                                  {
                                            return $query->where('uom_id','=',$request->uom);
                                  })
                                  ->when($request->product_name, function($query) use ($request)
                                  {
                                            return $query->where('product_name','like','%'.$request->product_name.'%');
                                  })
                                  ->whereNull('deleted_at')
                                  ->count();

            $data = Product::with('category','uom')
                              ->when($request->category, function($query) use ($request)
                              {
                                        return $query->where('product_cat_id','=',$request->category);
                              })
                              ->when($request->uom, function($query) use ($request)
                              {
                                        return $query->where('uom_id','=',$request->uom);
                              })
                              ->when($request->product_name, function($query) use ($request)
                              {
                                  return $query->where('product_name','like','%'.$request->product_name.'%');
                              })
                               ->whereNull('deleted_at')
                               ->offset($start)
                               ->limit($per_page)
                               ->orderBy('created_at','DESC')
                               ->get();

            $numPage = ceil($totalData / $per_page);
            $page       = $page;
            $perpage    = $per_page;
            $count      = $totalData;

            $array =
            [
              'page'    => $page,
              'perpage' => $perpage,
              'count'   => $count,
              'source'  => $request->source
            ];
            if($output=='HTML')
            {
                $returnHTML   = view('master.product.product_list',['data' => $data,'array' => $array])->render();
                $json['status']  = 'success';
                $json['output']  = 'HTML';
                $json['html']    = $returnHTML;
                $json['numPage'] = $numPage;
                $json['numitem'] = $count;
            }
            else
            {
                $returnHTML   = view('master.product.product_list',['data' => $data,'array' => $array])->render();
                $json['status']  = 'success';
                $json['output']  = 'EXCEL';
                $json['html']    = $returnHTML;
                $json['numPage'] = $numPage;
                $json['numitem'] = $count;
                if(!empty($request->category) && !empty($request->uom))
                {
                      $count_data      =  Product::with('user','category','uom','currency')
                                          ->when($request->category, function($query) use ($request)
                                          {
                                                    return $query->where('product_cat_id','=',$request->category);
                                          })
                                          ->when($request->uom, function($query) use ($request)
                                          {
                                                    return $query->where('uom_id','=',$request->uom);
                                          })
                                          ->whereNull('deleted_at')
                                          ->count();
                      if($count_data>=1)
                      {
                          $json['status_link'] = 'ok';
                          $json['link']    = 'product/export/'.$request->category.'/'.$request->uom.'';
                      }
                      else
                      {
                          $json['status_link'] = 'failed';
                          $json['msg']         = 'Data failed to be export excel, data is not available in the database, please check again!';
                      }

                }
                else if(!empty($request->category))
                {
                    $count_data      =  Product::with('user','category','uom','currency')
                                        ->when($request->category, function($query) use ($request)
                                        {
                                                  return $query->where('product_cat_id','=',$request->category);
                                        })
                                        ->whereNull('deleted_at')
                                        ->count();
                    if($count_data>=1)
                    {
                        $json['status_link'] = 'ok';
                        $json['link']    = 'product/'.$request->category.'';
                    }
                    else
                    {
                        $json['status_link'] = 'failed';
                        $json['msg']         = 'Data failed to be export excel, data is not available in the database, please check again!';
                    }

                }
                else if(!empty($request->uom))
                {
                    $count_data      =  Product::with('user','category','uom','currency')
                                        ->when($request->uom, function($query) use ($request)
                                        {
                                                  return $query->where('uom_id','=',$request->uom);
                                        })
                                        ->whereNull('deleted_at')
                                        ->count();
                    if($count_data>=1)
                    {
                        $json['status_link'] = 'ok';
                        $json['link']    = 'product_export/'.$request->uom.'';
                    }
                    else
                    {
                        $json['status_link'] = 'failed';
                        $json['msg']         = 'Data failed to be export excel, data is not available in the database, please check again!';
                    }

                }
                else if(!empty($request->product_name))
                {
                    $count_data = Product::with('user','category','uom','currency')
                                          ->when($request->product_name, function($query) use ($request)
                                          {
                                                    return $query->where('product_name','like','%'.$request->product_name.'%');
                                          })
                                          ->whereNull('deleted_at')
                                          ->count();
                    if($count_data>=1)
                    {
                        $json['status_link'] = 'ok';
                        $json['link']    = 'product/export/'.$request->product_name.'';
                    }
                    else
                    {
                        $json['status_link'] = 'failed';
                        $json['msg']         = 'Data failed to be export excel, data is not available in the database, please check again!';
                    }


                }
                else
                {
                  $count_data        = Product::with('user','category','uom','currency')
                                        ->whereNull('deleted_at')
                                        ->count();
                  if($count_data>=1)
                  {
                      $json['status_link'] = 'ok';
                      $json['link']      = 'product/export';
                  }
                  else
                  {
                    $json['status_link'] = 'failed';
                    $json['msg']         = 'Data failed to be export excel, data is not available in the database, please check again!';

                  }
                }


            }

              return response()->json($json);
    }

    public function export_category_uom($category,$uom)
    {
            $data  = Product::with('category','uom','currency')
                           ->when($category, function($query) use ($category)
                           {
                                     return $query->where('product_cat_id','=',$category);
                           })
                           ->when($uom, function($query) use ($uom)
                           {
                                     return $query->where('uom_id','=',$uom);
                           })
                            ->whereNull('deleted_at')
                            ->orderBy('created_at','DESC')
                            ->get();
           Excel::create('Product_'.convertdate(), function($excel) use ($data) {

               $excel->sheet('sheet1', function($sheet) use ($data) {
                   $sheet->loadView('master.product.export',compact('data'));
                   $sheet->setOrientation('landscape');
               });
           })->download('xlsx');
    }

    public function export_category($category)
    {
            $data  = Product::with('category','uom','currency')
                           ->when($category, function($query) use ($category)
                           {
                                     return $query->where('product_cat_id','=',$category);
                           })
                            ->whereNull('deleted_at')
                            ->orderBy('created_at','DESC')
                            ->get();
           Excel::create('Product_'.convertdate(), function($excel) use ($data) {

               $excel->sheet('sheet1', function($sheet) use ($data) {
                   $sheet->loadView('master.product.export',compact('data'));
                   $sheet->setOrientation('landscape');
               });
           })->download('xlsx');
    }

    public function export_uom($uom)
    {
            $data  = Product::with('category','uom','currency')
                           ->when($uom, function($query) use ($uom)
                           {
                                     return $query->where('uom_id','=',$uom);
                           })
                            ->whereNull('deleted_at')
                            ->orderBy('created_at','DESC')
                            ->get();
           Excel::create('Product_'.convertdate(), function($excel) use ($data) {

               $excel->sheet('sheet1', function($sheet) use ($data) {
                   $sheet->loadView('master.product.export',compact('data'));
                   $sheet->setOrientation('landscape');
               });
           })->download('xlsx');
    }

    public function export_product_name($product_name)
    {
         $data = Product::with('category','uom','currency')
                           ->when($product_name, function($query) use ($product_name)
                           {
                                     return $query->where('product_name','LIKE','%'.$product_name.'%');
                           })
                             ->whereNull('deleted_at')
                            ->orderBy('created_at','DESC')
                            ->get();
           Excel::create('Product_'.convertdate(), function($excel) use ($data) {

               $excel->sheet('sheet1', function($sheet) use ($data) {
                   $sheet->loadView('master.product.export',compact('data'));
                   $sheet->setOrientation('landscape');
               });
           })->download('xlsx');
    }
    public function export_all()
    {

         $data = Product::with('category','uom','currency')
                            ->whereNull('deleted_at')
                            ->orderBy('created_at','DESC')
                            ->get();

                  Excel::create('Product_'.convertdate(), function($excel) use ($data) {

                      $excel->sheet('sheet1', function($sheet) use ($data) {
                          $sheet->loadView('master.product.export',compact('data'));
                          $sheet->setOrientation('landscape');
                      });
                  })->download('xlsx');
    }

    public function add(Request $request)
    {
          $category   = Category::all();
          $uom        = UOM::all();
          $currency   = Currency::all();
          $payment    = Payment::all();
          $percentage = Percentage::all();
          $tax        = Tax::all();
          $returnHTML = view('master.product.add',compact('category','uom','currency','payment','percentage','tax'))->render();
          return response()->json(['status' =>'success','html' => $returnHTML]);
    }

    public function sales_price(Request $request)
    {

        $percentage           = $request->percentage;
        $disc                 = $request->disc;
        $tax                  = $request->tax;
        $action               = $request->action;
        $purchase_price       = $request->purchase_price;
        $hitung_percentage    = $purchase_price * $percentage/100;
        if($action=='1')
        {
            $HJT                           = ceil($purchase_price + $hitung_percentage);
        }
        if($action=='2')
        {
            $HJT = $request->sales_price;
        }
        else
        {
            $HJT = ceil($purchase_price + $hitung_percentage);
        }
        $histung_disc                  = $HJT * $disc/100;
        $HJT_after_disc                = ceil($HJT-$histung_disc);
        $hitung_ppn                    = $HJT_after_disc * $tax/100;

        if($action=='3')
        {
          $HJT_after_ppn = $request->total_sales;
          $HJT            = $request->sales_price;
        }
        else
        {
            $HJT_after_ppn                 = ceil($HJT_after_disc+$hitung_ppn);
        }
        $json['status']                = 'success';
        $json['percentage']            = $hitung_percentage;
        $json['hjt_val']               = $HJT;
        $json['hjt_format']            = number_format($HJT);
        $json['disc']                  = $histung_disc;
        $json['tax']                   = $hitung_ppn;
        $json['total_sales_price']     = number_format($HJT_after_ppn);
        $json['total_sales_price_val'] = $HJT_after_ppn;

        return response()->json($json);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(),$this->rules);
        if($validator->fails())
        {
            $json['status'] = 'error';
            $json['errors'] =  $validator->getMessageBag()->toArray();
            return response()->json($json);
        }
        else
        {
            $count = Product::where('barcode_id',$request->barcode_code)->count();
            if($count==0)
            {
                  $count_2 = Product::where('product_name',$request->product_name)->count();
                  if($count_2==0)
                  {
                      $product_id = autonumber('product','product_id','P');
                      $insert = Product::create([
                                'barcode_id'        => $request->barcode_code,
                                'product_id'        => $product_id,
                                'product_name'      => $request->product_name,
                                'product_cat_id'    => $request->product_category,
                                'uom_id'            => $request->uom,
                                'curry_id'          => $request->currency,
                                'payment_id'        => $request->payment,
                                'percentage'        => $request->percentage,
                                'percentage_value'  => $request->percentage_value,
                                'purchase_price'    => $request->purchase_price,
                                'sales_price'       => $request->sales_price,
                                'disc_percentage'   => $request->disc,
                                'disc_value'        => $request->disc_value,
                                'tax_percentage'    => $request->tax,
                                'tax_value'         => $request->tax_value,
                                'sales_total_price' => $request->total_sales_price,
                                'created_by'        => Auth::user()->id

                      ]);

                      if($insert)
                      {
                            $json['status'] = 'success';
                            $json['msg']    = 'Successfully added Post!';
                            activity_log(get_module_id('product'), 'Create', $request->product_name, 'Successfully added  !');
                      }
                  }
                  else
                  {

                      $json['status'] = 'failed';
                      $json['msg']    = 'Data failed to be stored , data '.$request->barcode_code.' is available in the database !';
                      activity_log(get_module_id('product'), 'Create', $request->barcode_code, 'Data failed to stored, available in the database ! ');
                  }
            }
            else
            {

                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->product_name.' is available in the database !';
                activity_log(get_module_id('product'), 'Create', $request->product_name, 'Data failed to stored, available in the database ! ');
            }
        }

        return response()->json($json);
    }

    public function edit(Request $request)
    {
        $count = Product::where('product_id',$request->id)->count();
        if($count==1)
        {
              $status     = 'success';
              $category   = Category::all();
              $uom        = UOM::all();
              $currency   = Currency::all();
              $payment    = Payment::all();
              $percentage = Percentage::all();
              $tax        = Tax::all();
              $row        = Product::where('product_id',$request->id)->first();
              $returnHTML = view('master.product.edit',compact('category','uom','currency','payment','percentage','tax','row'))->render();
              $msg        = '';
        }
        else
        {
          $status      = 'failed';
          $returnHTML  = '';
          $msg         = 'Data failed to be changed, data not found !';
          activity_log(get_module_id('product'), 'Alter', $request->id, 'Data failed to be changed, data not found !');
        }

        return response()->json(['status' => $status,'html' => $returnHTML,'msg' => $msg]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(Input::all(),$this->rules);
        if($validator->fails())
        {
            $json['status'] = 'error';
            $json['errors'] = $validator->getMessageBag()->toArray();
            return response()->json($json);
        }
        else
        {
            $count = Product::where('product_id',$request->id)->count();
            if($count==1)
            {
                $update = Product::where('product_id',$request->id)
                ->update([
                        'barcode_id'        => $request->barcode_code,
                        'product_name'      => $request->product_name,
                        'product_cat_id'    => $request->product_category,
                        'uom_id'            => $request->uom,
                        'curry_id'          => $request->currency,
                        'payment_id'        => $request->payment,
                        'percentage'        => $request->percentage,
                        'percentage_value'  => $request->percentage_value,
                        'purchase_price'    => $request->purchase_price,
                        'sales_price'       => $request->sales_price,
                        'disc_percentage'   => $request->disc,
                        'disc_value'        => $request->disc_value,
                        'tax_percentage'    => $request->tax,
                        'tax_value'         => $request->tax_value,
                        'sales_total_price' => $request->total_sales_price,
                        'updated_by'    => Auth::user()->id
                ]);
                if($update)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Successfully Updated Post!';
                    activity_log(get_module_id('product'), 'Alter', $request->id, 'Successfully update data !');

                }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->product_name.' data not found in the database !';
                activity_log(get_module_id('product'), 'Alter', $request->product_name, 'Data failed to be stored, data not found in the database  !');

            }
        }

        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count = Product::where('product_id',$request->id)->count();
        if($count==1)
        {
            $delete = Product::where('product_id',$request->id)->delete();
            if($delete)
            {
                $json['status'] = 'success';
                $json['msg']    = 'Data Successfully Deleted !';
                activity_log(get_module_id('product'), 'Drop', $request->id, 'Successfully deleted data !');

            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed Deleted !';
                activity_log(get_module_id('product'), 'Drop', $request->id, 'Data failed deleted !');
            }
        }
        else
        {
          $json['status'] = 'failed';
          $json['msg']    = 'Data not found !';
          activity_log(get_module_id('product'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
        }

        return response()->json($json);
    }


    public function upload_form()
    {
        $returnHTML = view('master.product.upload_form')->render();
        $json['status'] = 'success';
        $json['html']   = $returnHTML;
        return response()->json($json);
    }



    public function import_Excel(Request $request)
    {
          if (Input::hasFile('import_file'))
          {
              $path = Input::file('import_file')->getRealPath();
              $data = Excel::load($path, function($reader){})->get();
              if($data->count()>=1)
              {
                    foreach ($data as $rows)
                    {
                          foreach ($rows as $row)
                          {
                                $count = Product::where('barcode_id',$row->barcode)->count();
                                if($count==0)
                                {
                                    $product_id = autonumber('product','product_id','P');
                                    $insert = Product::create([
                                              'barcode_id'        => $row->barcode,
                                              'product_id'        => $product_id,
                                              'product_name'      => $row->name,
                                              'product_cat_id'    => $row->category_id,
                                              'uom_id'            => $row->uom_id,
                                              'curry_id'          => $row->curry_id,
                                              'payment_id'        => $row->payment_id,
                                              'purchase_price'    => $row->purchase_price,
                                              'percentage'        => $row->percentage,
                                              'percentage_value'  => $row->percentage_value,
                                              'sales_price'       => $row->sales_price,
                                              'disc_percentage'   => $row->disc_percentage,
                                              'disc_value'        => $row->disc_value,
                                              'tax_percentage'    => $row->tax_percentage,
                                              'tax_value'         => $row->tax_value,
                                              'sales_total_price' => $row->total_sales_price,
                                              'created_by'        => Auth::user()->id

                                    ]);

                                    if($insert)
                                    {
                                        $json['status'] = 'success';
                                        $json['msg']    = 'Data telah berhasil diupload pada sistem !';
                                    }
                                    else
                                    {
                                        $json['status'] = 'failed';
                                        $json['msg']    = 'Data gagal diupload, terjadi kesalahan pada koneksi database !';
                                    }

                                }
                                else
                                {
                                    $json['status'] = 'failed';
                                    $json['msg']    = 'Data yang diupload telah tersedia pada database !';
                                }

                          }
                    }
              }
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Silahkan pilih file excel terlebih dahulu ! ';
          }

          return response()->json($json);
    }





}
