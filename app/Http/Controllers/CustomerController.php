<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\CustClass;
use App\Models\Religion;
use Validator;
use Response;
use View;
use Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $rules =
    [
       'customer_class' => 'required|min:1|max:32',
       'customer_name'  => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
       'date_join'      => 'required',
       'gender'         => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
       'phone'          => 'required|numeric',
       'address'        => 'required',
       'city'           => 'required'
    ];

    public function index()
    {
          if(access_level_user('view','customer')=='allow')
          {
                activity_log(get_module_id('customer'), 'View', '', '');
                return view('master/customer/index');
          }
          else
          {
              activity_log(get_module_id('customer'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }
    }

    public function list_data(Request $request)
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
            $returnHTML   = view('master.customer.customer_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }

    public function add()
    {
        $customer_class = CustClass::all();
        $religion       = Religion::all();
        $returnHTML     = view('master.customer.add',compact('customer_class','religion'))->render();
        $status         = 'success';
        return response(['status' => $status,'html' => $returnHTML]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(),$this->rules);
        if($validator->fails())
        {
            $json['status']  = 'error';
            $json['errors']  = $validator->getMessageBag()->toArray();
            return response()->json($json);
        }
        else
        {
            $customer_id = autonumber('customer','customer_id','C');
            $insert = Customer::create([
                                        'customer_id'    => $customer_id,
                                        'cust_class_id'  => $request->customer_class,
                                        'customer_name'  => $request->customer_name,
                                        'place_of_birth' => $request->place_of_birth,
                                        'religion'       => $request->religion,
                                        'gender'         => $request->gender,
                                        'date_of_birth'  => date('Y-m-d',strtotime($request->date_of_birth)),
                                        'date_join'      => date('Y-m-d',strtotime($request->date_join)),
                                        'email'          => $request->email,
                                        'phone'          => $request->phone,
                                        'address'        => $request->address,
                                        'city'           => $request->city,
                                        'created_by'     => Auth::user()->id
                                      ]);
            if($insert)
            {
                    $json['status'] = 'success';
                    $json['msg']    = 'Successfully added Post !';
                    activity_log(get_module_id('customer'), 'Create', $request->customer_name, 'Successfully added  !');
            }

        }

        return response()->json($json);
    }

    public function edit(Request $request)
    {
        $count = Customer::where('customer_id',$request->id)->count();
        if($count==1)
        {

            $customer_class      = CustClass::all();
            $religion            = Religion::all();
            $row                 = Customer::where('customer_id',$request->id)->first();
            $returnHTML          = view('master.customer.edit',compact('customer_class','religion','row'))->render();
            $json['status']      = 'success';
            $json['html']        = $returnHTML;
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data failed to be changed, data not found !';
            activity_log(get_module_id('customer'), 'Alter', $request->id, 'Data failed to be changed, data not found !');

        }

        return response()->json($json);
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
            $count = Customer::where('customer_id',$request->id)->count();
            if($count==1)
            {
                $update = Customer::where('customer_id',$request->id)
                                    ->update([
                                                'cust_class_id'  => $request->customer_class,
                                                'customer_name'  => $request->customer_name,
                                                'place_of_birth' => $request->place_of_birth,
                                                'religion'       => $request->religion,
                                                'gender'         => $request->gender,
                                                'date_of_birth'  => date('Y-m-d',strtotime($request->date_of_birth)),
                                                'date_join'      => date('Y-m-d',strtotime($request->date_join)),
                                                'email'          => $request->email,
                                                'phone'          => $request->phone,
                                                'address'        => $request->address,
                                                'city'           => $request->city,
                                                'updated_by'     => Auth::user()->id
                                            ]);
                 if($update)
                 {
                     $json['status'] = 'success';
                     $json['msg']    = 'Successfully Updated Post!';
                     activity_log(get_module_id('customer'), 'Alter', $request->id, 'Successfully update data !');

                 }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->customer_name.' data not found in the database !';
                activity_log(get_module_id('customer'), 'Alter', $request->customer_name, 'Data failed to be stored, data not found in the database  !');

            }

        }

        return response()->json($json);

    }

    public function delete(Request $request)
    {
        $count = Customer::where('customer_id',$request->id)->count();
        if($count==1)
        {
              $delete = Customer::where('customer_id',$request->id)->delete();
              if($delete)
              {
                  $json['status'] = 'success';
                  $json['msg']    = 'Data Successfully Deleted !';
                  activity_log(get_module_id('customer'), 'Drop', $request->id, 'Successfully deleted data !');
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed Deleted !';
                  activity_log(get_module_id('customer'), 'Drop', $request->id, 'Data failed deleted !');
              }
        }
        else
        {
          $json['status'] = 'failed';
          $json['msg']    = 'Data not found !';
          activity_log(get_module_id('customer'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
        }

          return response()->json($json);
    }





}
