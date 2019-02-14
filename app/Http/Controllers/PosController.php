<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Counter;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Tax;
use App\Models\Product;
use App\Models\Category;
use App\Models\UOM;
use App\Models\Currency;

use App\Models\PosLine;
use App\Models\PosHeader;

use Validator;
use Response;
use View;
use Auth;

class PosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
          if(access_level_user('view','pos')=='allow')
          {
              activity_log(get_module_id('pos'), 'View', '', '');
              $customer = Customer::where('customer_id','C00001')->first();
              $payment  = Payment::where('payment_id','PM0001')->first();
              return view('transaction/pos/index',['customer' =>$customer,'payment' =>$payment]);
          }
          else
          {
              activity_log(get_module_id('pos'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }
    }

    public function table_item(Request $request)
    {
        $data = PosLine::with('product')
                         ->where('trans_id',$request->transaction_number)
                         ->whereNull('deleted_at');
        $pos_line_row = PosLine::select(DB::raw('SUM(qty)as qty,SUM(sales_price*qty)as sub_total,
                                                SUM(purchase_price*qty)as sub_total_purchase,
                                                (SUM(sales_price*qty)-SUM(purchase_price*qty))as laba,
                                                MAX(trans_line_id)as trans_line_id'))
                                  ->where('trans_id',$request->transaction_number)
                                  ->whereNull('deleted_at')
                                  ->first();
        $returnHTML = view('transaction.pos.table',compact('data','pos_line_row'))->render();
        return response()->json(['status' =>'success','html' => $returnHTML]);
    }

    public function get_product()
    {

        $category = Category::all();
        $uom      = UOM::all();
        $currency = Currency::all();

        $returnHTML = view('transaction.pos.product',compact('category','uom','currency'))->render();
        $json['list'] = $returnHTML;
        return response()->json($json);
    }

    public function get_customer()
    {
        $returnHTML   = view('transaction.pos.customer')->render();
        $json['list'] = $returnHTML;
        return response()->json($json);
    }

    public function get_payment()
    {
        $returnHTML   =  view('transaction.pos.payment')->render();
        $json['list'] = $returnHTML;
        return response()->json($json);
    }

    public function list_data_product(Request $request)
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
                $returnHTML   = view('transaction.pos.product_list',['data' => $data,'array' => $array])->render();
                $json['status']  = 'success';
                $json['output']  = 'HTML';
                $json['html']    = $returnHTML;
                $json['numPage'] = $numPage;
                $json['numitem'] = $count;
            }


              return response()->json($json);
    }

    public function list_data_customer(Request $request)
    {
            $page = $request->page;
            $per_page = 10;
            if ($page != 1) $start = ($page-1) * $per_page;
            else $start=0;

            $totalData = Customer::with('user','custclass')
                                  ->when($request->customer_name, function($query) use ($request)
                                  {
                                            return $query->where('customer_name','like','%'.$request->customer_name.'%');
                                  })
                                  ->whereNull('deleted_at')
                                  ->count();

            $data = Customer::with('user','custclass')
                              ->when($request->customer_name, function($query) use ($request)
                              {
                                  return $query->where('customer_name','like','%'.$request->customer_name.'%');
                              })
                               ->whereNull('deleted_at')
                               ->offset($start)
                               ->limit($per_page)
                               ->orderBy('created_at','ASC')
                               ->get();

            $numPage = ceil($totalData / $per_page);
            $page       = $page;
            $perpage    = $per_page;
            $count      = $totalData;

            $array =
            [
              'page'    => $page,
              'perpage' => $perpage,
              'count'   => $count
            ];
            $returnHTML   = view('transaction.pos.customer_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }

    public function list_data_payment(Request $request)
    {
            $page = $request->page;
            $per_page = 10;
            if ($page != 1) $start = ($page-1) * $per_page;
            else $start=0;

            $totalData = Payment::with('user')
                                  ->when($request->payment, function($query) use ($request)
                                  {
                                            return $query->where('payment_method','like','%'.$request->payment.'%');
                                  })
                                  ->whereNull('deleted_at')
                                  ->count();

            $data = Payment::with('user')
                              ->when($request->payment, function($query) use ($request)
                              {
                                  return $query->where('payment_method','like','%'.$request->payment.'%');
                              })
                               ->whereNull('deleted_at')
                               ->offset($start)
                               ->limit($per_page)
                               ->orderBy('payment_id','ASC')
                               ->get();

            $numPage = ceil($totalData / $per_page);
            $page       = $page;
            $perpage    = $per_page;
            $count      = $totalData;

            $array =
            [
              'page'    => $page,
              'perpage' => $perpage,
              'count'   => $count
            ];
            $returnHTML   = view('transaction.pos.payment_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }


    public function add_customer(Request $request)
    {
        $count = Customer::where('customer_id',$request->customer_id)
                           ->whereNull('deleted_at');

        if($count->count()==1)
        {
            $row = $count->first();
            $json['status']        = 'success';
            $json['customer_id']   = $row->customer_id;
            $json['customer_name'] = $row->customer_name;
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data customer tidak ditemukan, silahkan cek kembali !';
        }

        return response()->json($json);
    }

    public function add_payment(Request $request)
    {
        $data = Payment::where('payment_id',$request->payment_id)
                          ->whereNull('deleted_at');
        if($data->count()==1)
        {

            $row = $data->first();
            $json['payment_id']     = $row->payment_id;
            $json['payment_method'] = $row->payment_method;
            $json['status']         = 'success';
        }
        else
        {
           $json['status']  = 'failed';
           $json['msg']     = 'Data tidak ditemukan, silahkan cek kembali !';
        }

        return response()->json($json);
    }
    public function add_product(Request $request)
    {
          $count_product =  Product::with('category','uom')
                           ->when($request->product_id, function($query) use ($request)
                            {
                                      return $query->where('product_id','=',$request->product_id);
                            })
                            ->when($request->barcode_id, function($query) use ($request)
                            {
                                      return $query->where('barcode_id','=',$request->barcode_id);
                            })

                           ->whereNull('deleted_at')
                           ->count();
            if($count_product>=1)
            {

                  if($request->transaction_no=='')
                  {
                      $transaction_no = autonumber_transaction('pos_header','trans_id','P');
                  }
                  else
                  {
                      $transaction_no = $request->transaction_no;
                  }

                  $count_pos_header     = PosHeader::where('trans_id',$transaction_no)
                                                    ->whereNull('deleted_at')
                                                    ->count();
                  if($count_pos_header==0)
                  {
                        $insert_pos_header = PosHeader::create([
                          'trans_id'    => $transaction_no,
                          'trans_date'  => \Carbon\Carbon::now(),
                          'cust_id'     => $request->customer_id,
                          'cust_name'   => $request->customer_name,
                          'payment_id'  => $request->payment_id,
                          'created_by'  => Auth::user()->id
                        ]);
                  }
                  # Cek transaksi pos header
                  $count_exist_pos_header    = PosHeader::where('trans_id',$transaction_no)
                                                          ->whereNull('deleted_at')
                                                          ->count();
                  if($count_exist_pos_header==1)
                  {
                        $count =  Product::with('category','uom','currency')
                                         ->when($request->product_id, function($query) use ($request)
                                          {
                                                    return $query->where('product_id','=',$request->product_id);
                                          })
                                          ->when($request->barcode_id, function($query) use ($request)
                                          {
                                                    return $query->where('barcode_id','=',$request->barcode_id);
                                          })

                                         ->whereNull('deleted_at')
                                         ->count();
                        if($count==1)
                        {
                                $row =  Product::with('category','uom','currency')
                                                 ->when($request->product_id, function($query) use ($request)
                                                  {
                                                            return $query->where('product_id','=',$request->product_id);
                                                  })
                                                  ->when($request->barcode_id, function($query) use ($request)
                                                  {
                                                            return $query->where('barcode_id','=',$request->barcode_id);
                                                  })

                                                 ->whereNull('deleted_at')
                                                 ->first();

                                $insert_pos_line = PosLine::create([
                                                                  'trans_line_id'      => autonumber_transaction_line('pos_line','trans_line_id','PD','trans_id',$transaction_no),
                                                                  'trans_id'            => $transaction_no,
                                                                  'product_id'          => $row->product_id,
                                                                  'product_name'        => $row->product_name,
                                                                  'qty'                 => 1,
                                                                  'uom'                 => $row->uom->uom_name,
                                                                  'currency'            => $row->currency->currency_name,
                                                                  'sales_price'         => $row->sales_price,
                                                                  'purchase_price'      => $row->purchase_price,
                                                                  'sub_total_purchase'  => $row->purchase_price,
                                                                  'disc'                => $row->disc_percentage,
                                                                  'disc_value'          => $row->disc_value,
                                                                  'ppn'                 => $row->tax_percentage,
                                                                  'ppn_value'           => $row->tax_value,
                                                                  'sub_total_sales'     => $row->sales_total_price,
                                                                  'created_by'          => Auth::user()->id,
                                                                  ]);

                               if($insert_pos_line)
                               {
                                     $json['transaction_no'] = $transaction_no;
                                     $json['product_id']     = $row->product_id;
                                     $json['barcode']        = $row->barcode_id;
                                     $json['product_name']   = $row->product_name;
                                     $json['uom_name']       = $row->uom->uom_name;
                                     $json['qty']            = 1;
                                     $json['sales_price']    = number_format($row->sales_price);
                                     $json['sales_price_val']= $row->sales_price;
                                     $json['sub_total_val']  = $row->sales_total_price;
                                     $json['disc']           = $row->disc_percentage;
                                     $json['ppn']            = $row->tax_percentage;
                                     $json['sub_total']      = number_format($row->sales_total_price);


                                     $max_number = PosLine::where('trans_id',$transaction_no)
                                                            ->whereNull('deleted_at')
                                                            ->count();
                                     if($max_number==0):
                                       $no_urut = 1;
                                     else:
                                         $no_urut = $max_number;
                                     endif;

                                     $pos_line_row = PosLine::select(DB::raw('SUM(qty)as qty,SUM(sub_total_sales)as sub_total,
                                                                              SUM(purchase_price*qty)as sub_total_purchase,
                                                                              (SUM(sub_total_sales)-SUM(purchase_price*qty))as laba,
                                                                              MAX(trans_line_id)as trans_line_id'))
                                                              ->where('trans_id',$transaction_no)
                                                              ->whereNull('deleted_at')
                                                              ->first();
                                     $update_pos_header = PosHeader::where('trans_id',$transaction_no)
                                     ->update([
                                      'grand_qty'            => $pos_line_row->qty,
                                      'grand_sales_price'    => $pos_line_row->sub_total,
                                      'grand_purchase_price' => $pos_line_row->sub_total_purchase,
                                      'grand_laba'           => $pos_line_row->laba,
                                      'stat_pay'             => 'N'
                                    ]);

                                      $laba = PosLine::select(DB::raw('(SUM(sub_total_sales*qty)-SUM(purchase_price*qty))as laba'))
                                                            ->where('trans_id',$transaction_no)
                                                            ->where('trans_line_id',$pos_line_row->trans_line_id)
                                                            ->whereNull('deleted_at')
                                                            ->first();

                                      $update_2  = PosLine::where('trans_id',$transaction_no)
                                                            ->where('trans_line_id',$pos_line_row->trans_line_id)
                                                            ->whereNull('deleted_at')
                                                            ->update([
                                                              'laba_item'          => $laba->laba
                                                            ]);

                                     $json['subtotal']      = number_format($pos_line_row->sub_total);
                                     $json['total_qty']     = number_format($pos_line_row->qty);
                                     $json['total_sales']   = number_format($pos_line_row->sub_total);
                                     $json['trans_line_id'] = $pos_line_row->trans_line_id;
                                     $json['max_no']        = $no_urut;
                                     $json['status']        = 'success';
                               }
                        }
                        else
                        {
                            $json['status'] = 'failed';
                            $json['msg']    = 'Maaf, transaksi tidak ditemukan data gagal disimpan !';
                        }

                  }

            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Maaf data produk tidak ditemukan, transaksi gagal disimpan !';
            }




            return response()->json($json);
    }


    public function update(Request $request)
    {
          $count = PosLine::where('trans_id',$request->trans_id)
                            ->where('trans_line_id',$request->trans_line_id)
                            ->whereNull('deleted_at')
                            ->count();

          if($count==1)
          {
              $update = PosLine::where('trans_id',$request->trans_id)
                                 ->where('trans_line_id',$request->trans_line_id)
                                 ->whereNull('deleted_at')
                                 ->update([
                                   'qty'        => $request->qty,
                                   'disc'       => $request->disc,
                                   'ppn'        => $request->ppn,
                                   'updated_by' => Auth::user()->id
                                 ]);
              if($update)
              {
                  $row = PosLine::select(DB::raw('SUM(disc)as disc_value,SUM(ppn)as ppn_value,((SUM(net_price) - SUM(disc)) + SUM(ppn)) AS sub_total,SUM(purchase_price)AS purchase_price,(SUM(net_price)-SUM(disc)+SUM(ppn))-SUM(purchase_price)AS laba'))
                  ->from(DB::raw('(SELECT trans_id,trans_line_id,deleted_at,SUM(purchase_price*qty)AS purchase_price,SUM(sales_price*qty)AS net_price,(SUM(sales_price * qty)*SUM(disc/100)) AS disc,(SUM(sales_price * qty)*SUM(ppn/100)) AS ppn FROM pos_line WHERE trans_id="'.$request->trans_id.'"
                                   AND trans_line_id = "'.$request->trans_line_id.'") AS pos_line'))
                  ->groupBy('trans_id','trans_line_id')
                  ->first();

                  $update_2  = PosLine::where('trans_id',$request->trans_id)
                                        ->where('trans_line_id',$request->trans_line_id)
                                        ->whereNull('deleted_at')
                                        ->update([
                                          'disc_value'         => $row->disc_value,
                                          'ppn_value'          => $row->ppn_value,
                                          'sub_total_sales'    => $row->sub_total,
                                          'sub_total_purchase' => $row->purchase_price,
                                          'laba_item'          => $row->laba
                                        ]);

                  $grand_total = PosLine::select(DB::raw('SUM(qty)as qty,SUM(disc)as disc_value,SUM(ppn)as ppn_value,((SUM(net_price) - SUM(disc)) + SUM(ppn)) AS sub_total,SUM(purchase_price)AS purchase_price,(SUM(net_price)-SUM(disc)+SUM(ppn))-SUM(purchase_price)AS laba'))
                  ->from(DB::raw('(SELECT trans_id,trans_line_id,deleted_at,SUM(qty)as qty,SUM(purchase_price*qty)AS purchase_price,SUM(sales_price*qty)AS net_price,(SUM(sales_price * qty)*SUM(disc/100)) AS disc,(SUM(sales_price * qty)*SUM(ppn/100)) AS ppn FROM pos_line WHERE trans_id="'.$request->trans_id.'") AS pos_line'))
                  ->groupBy('trans_id')
                  ->first();

                  $update_pos_header = PosHeader::where('trans_id',$request->trans_id)
                  ->update([
                   'grand_qty'            => $grand_total->qty,
                   'grand_disc'           => $grand_total->disc_value,
                   'grand_ppn'            => $grand_total->ppn_value,
                   'grand_sales_price'    => $grand_total->sub_total,
                   'grand_purchase_price' => $grand_total->purchase_price,
                   'grand_laba'           => $grand_total->laba,
                   'updated_by'           => Auth::user()->id
                 ]);

                  $json['status']        = 'success';
                  $json['subtotal']     = number_format($row->sub_total);
                  $json['total_qty']     = number_format($grand_total->qty);
                  $json['total_sales']   = number_format($grand_total->sub_total);

              }
          }

          return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count = PosLine::where('trans_id',$request->trans_id)
                          ->where('trans_line_id',$request->trans_line_id)
                          ->whereNull('deleted_at')
                          ->count();
        if($count==1)
        {
            $delete = PosLine::where('trans_id',$request->trans_id)
                              ->where('trans_line_id',$request->trans_line_id)
                              ->whereNull('deleted_at')
                              ->delete();
            if($delete)
            {
              $grand_total = PosLine::select(DB::raw('SUM(qty) AS qty,
                                                      SUM(sales_price*qty)as sales_price,
                                                      SUM(purchase_price*qty)as purchase_price,
                                                      (SUM(sales_price*qty)-SUM(purchase_price*qty))as laba'))
                                      ->where('trans_id',$request->trans_id)
                                      ->whereNull('deleted_at')
                                      ->first();
                $json['total_sales']   = number_format($grand_total->sales_price);
                $json['status'] = 'success';
            }
        }
        else
        {
          $json['status'] = 'failed';
          $json['msg']    = 'Data tidak ditemukan, data gagal dihapus !';
        }

        return response()->json($json);
    }

    public function payment(Request $request)
    {
        $data = PosHeader::where('trans_id',$request->transaction_number)
                            ->whereNull('deleted_at');

        if($data->count()==1)
        {
            $row = $data->first();
            $returnHTML = view('transaction.pos.payment_form',compact('row'))->render();
            $json['status'] = 'success';
            $json['html']   = $returnHTML;
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Pembayaran ditolak, transaksi tidak ditemukan !';
        }

        return response()->json($json);
    }

    public function payment_action(Request $request)
    {
          $data = PosHeader::where('trans_id',$request->transaction_number)
                              ->whereNull('deleted_at');
          if($data->count()==1)
          {
              $payment = PosHeader::where('trans_id',$request->transaction_number)
                                    ->whereNull('deleted_at')
                                    ->update([
                                      'total_bayar' => $request->payment_value,
                                      'stat_pay'    => 'Y',
                                      'updated_by'  => Auth::user()->id
                                    ]);
              if($payment)
              {
                  $kembali = PosHeader::select(DB::raw('SUM(total_bayar-grand_sales_price) AS kembali'))
                                        ->where('trans_id',$request->transaction_number)
                                        ->whereNull('deleted_at')
                                        ->first();
                  $update = PosHeader::where('trans_id',$request->transaction_number)
                                       ->whereNull('deleted_at')
                                       ->update([
                                        'kembali' => $kembali->kembali,
                                       ]);
                  $head = PosHeader::with('payment')
                                     ->where('trans_id',$request->transaction_number)
                                     ->whereNull('deleted_at')->first();
                  $line = PosLine::where('trans_id',$request->transaction_number)
                                   ->whereNull('deleted_at')->get();
                  $returnHTML = view('transaction.pos.nota',compact('head','line'))->render();
                  $json['html']   = $returnHTML;
                  $json['status'] = 'success';
              }
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Pembayaran ditolak, transaksi tidak ditemukan !';

          }

        return response()->json($json);
    }

}
