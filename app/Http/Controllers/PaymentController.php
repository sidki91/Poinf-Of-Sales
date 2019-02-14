<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Validator;
use Response;
use View;
use Auth;

class PaymentController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [
            'payment_method' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
      ];

      public function index()
      {
            if(access_level_user('view','payment')=='allow')
            {
                activity_log(get_module_id('payment'), 'View', '', '');
                return view('master/payment/index');
            }
            else
            {
                activity_log(get_module_id('payment'), 'View', '', 'Error 403 : Forbidden');
                abort(403);
            }
      }

      public function list_data(Request $request)
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
              $returnHTML   = view('master.payment.payment_list',['data' => $data,'array' => $array])->render();
              return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
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
              $count = Payment::where('payment_method',$request->payment_method)->count();
              if($count==0)
              {
                  $payment_id = autonumber('payment_method','payment_id','PM');
                  $insert = Payment::create([
                              'payment_id'     => $payment_id,
                              'payment_method' => $request->payment_method,
                              'created_by'     => Auth::user()->id
                  ]);
                  if($insert)
                  {
                      $json['status'] = 'success';
                      $json['msg']    = 'Successfully added Post !';
                      activity_log(get_module_id('payment'), 'Create', $request->payment_method, 'Successfully added  !');

                  }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->payment_method.' is available in the database !';
                  activity_log(get_module_id('payment'), 'Create', $request->payment_method, 'Data failed to stored, available in the database ! ');

              }
          }

          return response()->json($json);
      }

      public function edit(Request $request)
      {
          $count = Payment::where('payment_id',$request->id)->count();
          if($count==1)
          {
              $status     = 'success';
              $row        = Payment::where('payment_id',$request->id)->first();
              $returnHTML = view('master.payment.edit',['row' => $row])->render();
              $msg        = '';
          }
          else
          {
              $status      = 'failed';
              $returnHTML  = '';
              $msg         = 'Data failed to be changed, data not found !';
              activity_log(get_module_id('payment'), 'Alter', $request->id, 'Data failed to be changed, data not found !');

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
              $count = Payment::where('payment_id',$request->id)->count();
              if($count ==1)
              {
                    $update = Payment::where('payment_id',$request->id)
                                       ->update([
                                          'payment_method' => $request->payment_method,
                                          'updated_by'     => Auth::user()->id
                                       ]);
                    if($update)
                    {
                        $json['status'] = 'success';
                        $json['msg']    = 'Successfully Update !';
                        activity_log(get_module_id('payment'), 'Alter', $request->id, 'Successfully update data !');

                    }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->payment_method.' data not found in the database !';
                  activity_log(get_module_id('payment'), 'Alter', $request->payment_method, 'Data failed to be stored, data not found in the database  !');
              }
          }

          return response()->json($json);
      }

      public function delete(Request $request)
      {
          $count = Payment::where('payment_id',$request->id)->count();
          if($count==1)
          {
              $delete = Payment::where('payment_id',$request->id)->delete();
              if($delete)
              {
                  $json['status'] = 'success';
                  $json['msg']    = 'Data Successfully Deleted !';
                  activity_log(get_module_id('payment'), 'Drop', $request->id, 'Successfully deleted data !');
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed Deleted !';
                  activity_log(get_module_id('payment'), 'Drop', $request->id, 'Data failed deleted !');
              }
          }
          else
          {
                $json['status'] = 'failed';
                $json['msg']    = 'Data not found !';
                activity_log(get_module_id('payment'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
          }

          return response()->json($json);
      }
}
