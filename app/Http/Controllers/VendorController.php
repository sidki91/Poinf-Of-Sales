<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor;
use App\Models\VendorClass;
use App\Models\Payment;
use App\Models\TermsPayment;
use App\Models\Currency;

use Validator;
use Response;
use View;
use Auth;


class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $rules =
    [
       'vendor_class'   => 'required|min:1|max:32',
       'vendor_name'    => 'required|min:1|max:32',
       'attention'      => 'required',
       'address'        => 'required',
       'city'           => 'required',
       'payment_method' => 'required',
       'terms_payment'  => 'required',
       'currency'       => 'required'
    ];

    public function index()
    {
          if(access_level_user('view','vendor')=='allow')
          {
                activity_log(get_module_id('vendor'), 'View', '', '');
                return view('master/vendor/index');
          }
          else
          {
              activity_log(get_module_id('vendor'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }
    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $per_page = 10;
            if ($page != 1) $start = ($page-1) * $per_page;
            else $start=0;

            $totalData = Vendor::with('user','classVendor')
                                  ->when($request->vendor_name, function($query) use ($request)
                                  {
                                            return $query->where('vendor_name','like','%'.$request->vendor_name.'%');
                                  })
                                  ->whereNull('deleted_at')
                                  ->count();

            $data = Vendor::with('user','classVendor')
                              ->when($request->vendor_name, function($query) use ($request)
                              {
                                  return $query->where('vendor_name','like','%'.$request->vendor_name.'%');
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
            $returnHTML   = view('master.vendor.vendor_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }

    public function add()
    {
        $vendor_class   = VendorClass::all();
        $payment        = Payment::all();
        $terms_payment  = TermsPayment::all();
        $currency       = Currency::all();
        $returnHTML     = view('master.vendor.add',compact('vendor_class','payment','terms_payment','currency'))->render();
        $status         = 'success';
        return response(['status' => $status,'html' => $returnHTML]);
    }

    public function store(Request $request)
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
            $count = Vendor::where('vendor_name',$request->vendor_name)->count();
            if($count==0)
            {
                  $vendor_id  = autonumber('vendor','vendor_id','V');
                  $insert = Vendor::create([
                                              'vendor_id'       => $vendor_id,
                                              'vendor_class_id' => $request->vendor_class,
                                              'vendor_name'     => $request->vendor_name,
                                              'bussiness_name'  => $request->bussiness_name,
                                              'attention'       => $request->attention,
                                              'email'           => $request->email,
                                              'web'             => $request->website,
                                              'phone'           => $request->phone,
                                              'address'         => $request->address,
                                              'city'            => $request->city,
                                              'postal_code'     => $request->postal_code,
                                              'payment_method'  => $request->payment_method,
                                              'terms_payment'   => $request->terms_payment,
                                              'curry_id'        => $request->currency,
                                              'tax_reg_id'      => $request->tax_reg,
                                              'tax_type'        => $request->tax_type,
                                              'created_by'      => Auth::user()->id
                                          ]);
                  if($insert)
                  {
                        $json['status'] = 'success';
                        $json['msg']    = 'Successfully added Post !';
                        activity_log(get_module_id('vendor'), 'Create', $request->vendor_name, 'Successfully added  !');

                  }
            }
            else
            {

                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->vendor_name.' is available in the database !';
                activity_log(get_module_id('vendor'), 'Create', $request->vendor_name, 'Data failed to stored, available in the database ! ');
            }
        }

        return response()->json($json);
    }

    public function edit(Request $request)
    {
        $count = Vendor::where('vendor_id',$request->id)->count();
        if($count==1)
        {
                $vendor_class   = VendorClass::all();
                $payment        = Payment::all();
                $terms_payment  = TermsPayment::all();
                $currency       = Currency::all();
                $row            = Vendor::where('vendor_id',$request->id)->first();
                $returnHTML     = view('master.vendor.edit',
                                  compact('vendor_class','payment','terms_payment','currency','row'))
                                  ->render();
                $json['status'] = 'success';
                $json['html']   = $returnHTML;
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data failed to be changed, data not found !';
            activity_log(get_module_id('vendor'), 'Alter', $request->id, 'Data failed to be changed, data not found !');
        }

        return response()->json($json);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(Input::all(),$this->rules);
        if($validator->fails())
        {
            $json['status'] = 'error';
            $json['erros']  = $validator->getMessageBag()->toArray();
            return response()->json($json);
        }
        else
        {
            $count = Vendor::where('vendor_id',$request->id)->count();
            if($count==1)
            {

                      $update = Vendor::where('vendor_id',$request->id)
                                        ->update([
                                                    'vendor_class_id' => $request->vendor_class,
                                                    'vendor_name'     => $request->vendor_name,
                                                    'bussiness_name'  => $request->bussiness_name,
                                                    'attention'       => $request->attention,
                                                    'email'           => $request->email,
                                                    'web'             => $request->website,
                                                    'phone'           => $request->phone,
                                                    'address'         => $request->address,
                                                    'city'            => $request->city,
                                                    'postal_code'     => $request->postal_code,
                                                    'payment_method'  => $request->payment_method,
                                                    'terms_payment'   => $request->terms_payment,
                                                    'curry_id'        => $request->currency,
                                                    'tax_reg_id'      => $request->tax_reg,
                                                    'tax_type'        => $request->tax_type,
                                                    'updated_by'      => Auth::user()->id
                                                ]);
                      if($update)
                      {
                          $json['status'] = 'success';
                          $json['msg']    = 'Successfully Updated Post!';
                          activity_log(get_module_id('vendor'), 'Alter', $request->id, 'Successfully update data !');

                      }

            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->vendor_name.' data not found in the database !';
                activity_log(get_module_id('vendor'), 'Alter', $request->vendor_name, 'Data failed to be stored, data not found in the database  !');
            }
        }

        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count = Vendor::where('vendor_id',$request->id)->count();
        if($count==1)
        {
            $delete = Vendor::where('vendor_id',$request->id)->delete();
            if($delete)
            {
                $json['status'] = 'success';
                $json['msg']    = 'Data Successfully Deleted !';
                activity_log(get_module_id('vendor'), 'Drop', $request->id, 'Successfully deleted data !');

            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed Deleted !';
                activity_log(get_module_id('vendor'), 'Drop', $request->id, 'Data failed deleted !');
            }
        }
        else
        {
              $json['status'] = 'failed';
              $json['msg']    = 'Data not found !';
              activity_log(get_module_id('vendor'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
        }

        return response()->json($json);
    }
}
