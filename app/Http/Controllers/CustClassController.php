<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustClass;
use Validator;
use Response;
use View;
use Auth;

class CustClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $rules =
    [
        'customer_class' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
    ];

    public function index()
    {
          if(access_level_user('view','customer_class')=='allow')
          {
                activity_log(get_module_id('customer_class'), 'View', '', '');
                return view('master/custclass/index');
          }
          else
          {
              activity_log(get_module_id('customer_class'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }
    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $per_page = 10;
            if ($page != 1) $start = ($page-1) * $per_page;
            else $start=0;

            $totalData = CustClass::with('user')
                                  ->when($request->customer_class, function($query) use ($request)
                                  {
                                            return $query->where('customer_class','like','%'.$request->customer_class.'%');
                                  })
                                  ->whereNull('deleted_at')
                                  ->count();

            $data = CustClass::with('user')
                              ->when($request->customer_class, function($query) use ($request)
                              {
                                  return $query->where('customer_class','like','%'.$request->customer_class.'%');
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
            $returnHTML   = view('master.custclass.custclass_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
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
            $count = custClass::where('customer_class',$request->customer_class)->count();
            if($count==0)
            {
                  $customer_class_id = autonumber('customer_class','customer_class_id','CC');
                  $insert = custClass::create([
                                                  'customer_class_id' => $customer_class_id,
                                                  'customer_class'    => $request->customer_class,
                                                  'created_by'        => Auth::user()->id
                                             ]);
                  if($insert)
                  {
                      $json['status'] = 'success';
                      $json['msg']    = 'Successfully added Post !';
                      activity_log(get_module_id('customer_class'), 'Create', $request->customer_class, 'Successfully added  !');
                  }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->customer_class.' is available in the database !';
                activity_log(get_module_id('customer_class'), 'Create', $request->customer_class, 'Data failed to stored, available in the database ! ');

            }
        }

        return response()->json($json);
    }

    public function edit(Request $request)
    {
        $count = CustClass::where('customer_class_id',$request->id)->count();
        if($count==1)
        {
            $status     = 'success';
            $row        = custClass::where('customer_class_id',$request->id)->first();
            $returnHTML = view('master.custclass.edit')->with('row',$row)->render();
            $msg        = '';
        }
        else
        {
            $status      = 'failed';
            $returnHTML  = '';
            $msg         = 'Data failed to be changed, data not found !';
            activity_log(get_module_id('customer_class'), 'Alter', $request->id, 'Data failed to be changed, data not found !');

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
            $count = custClass::where('customer_class_id',$request->id)->count();
            if($count==1)
            {
                $count_2 = custClass::where('customer_class',$request->customer_class)->count();
                if($count_2==0)
                {
                      $update = custClass::where('customer_class_id',$request->id)
                                           ->update([
                                                      'customer_class' => $request->customer_class,
                                                      'updated_by'     => Auth::user()->id
                                                    ]);
                      if($update)
                      {
                          $json['status'] = 'success';
                          $json['msg']    = 'Successfully Update !';
                          activity_log(get_module_id('customer_class'), 'Alter', $request->id, 'Successfully update data !');

                      }
                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data failed to be stored , data '.$request->customer_class.' is available in the database !';
                    activity_log(get_module_id('customer_class'), 'Create', $request->customer_class, 'Data failed to stored, available in the database ! ');
                }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->customer_class.' data not found in the database !';
                activity_log(get_module_id('customer_class'), 'Alter', $request->customer_class, 'Data failed to be stored, data not found in the database  !');
            }
        }

          return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count = custClass::where('customer_class_id',$request->id)->count();
        if($count==1)
        {
              $delete = custClass::where('customer_class_id',$request->id)->delete();
              if($delete)
              {
                  $json['status'] = 'success';
                  $json['msg']    = 'Data Successfully Deleted !';
                  activity_log(get_module_id('customer_class'), 'Drop', $request->id, 'Successfully deleted data !');

              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed Deleted !';
                  activity_log(get_module_id('customer_class'), 'Drop', $request->id, 'Data failed deleted !');

              }
        }
        else
        {
              $json['status'] = 'failed';
              $json['msg']    = 'Data not found !';
              activity_log(get_module_id('customer_class'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
        }

        return response()->json($json);
    }
}
