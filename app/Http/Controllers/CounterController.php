<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Counter;
use Validator;
use Response;
use View;
use Auth;

class CounterController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [
              'counter' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
      ];

      public function index()
      {
            if(access_level_user('view','counter')=='allow')
            {
                  activity_log(get_module_id('counter'), 'View', '', '');
                  return view('master/counter/index');
            }
            else
            {
                activity_log(get_module_id('counter'), 'View', '', 'Error 403 : Forbidden');
                abort(403);
            }

      }

      public function list_data(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
            	if ($page != 1) $start = ($page-1) * $per_page;
            	else $start=0;

              $totalData = Counter::with('user')
                                    ->when($request->counter, function($query) use ($request)
                                    {
                                              return $query->where('counter_name','like','%'.$request->section.'%');
                                    })
                                    ->whereNull('deleted_at')
                                    ->count();

              $data = Counter::with('user')
                                ->when($request->counter, function($query) use ($request)
                                {
                                    return $query->where('counter_name','like','%'.$request->counter.'%');
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
              $returnHTML   = view('master.counter.counter_list',['data' => $data,'array' => $array])->render();
              return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
      }

      Public function store(Request $request)
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
                $count = Counter::where('counter_name',$request->counter)->count();
                if($count==0)
                {
                      $counter_id = autonumber('counter','counter_id','K');
                      $insert = Counter::create([
                                                  'counter_id'    => $counter_id,
                                                  'counter_name'  => $request->counter,
                                                  'created_by'    => Auth::user()->id
                                                ]);
                      if($insert)
                      {
                          $json['status'] = 'success';
                          $json['msg']    = 'Successfully added Post !';
                          activity_log(get_module_id('counter'), 'Create', $request->counter, 'Successfully added  !');
                      }
                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data failed to be stored , data '.$request->counter.' is available in the database !';
                    activity_log(get_module_id('counter'), 'Create', $request->counter, 'Data failed to stored, available in the database ! ');

                }
            }

            return response()->json($json);
      }

      public function edit(Request $request)
      {
          $count = Counter::where('counter_id',$request->id)->count();
          if($count==1)
          {
              $status     = 'success';
              $row        = Counter::where('counter_id',$request->id)->first();
              $returnHTML = view('master.counter.edit')->with('row',$row)->render();
              $msg        = '';
          }
          else
          {
              $status      = 'failed';
              $returnHTML  = '';
              $msg         = 'Data failed to be changed, data not found !';
              activity_log(get_module_id('counter'), 'Alter', $request->id, 'Data failed to be changed, data not found !');

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

                $count  = Counter::where('counter_id',$request->id)->count();
                if($count==1)
                {
                    $count_2 = Counter::where('counter_name',$request->counter)->count();
                    if($count_2 == 0)
                    {
                          $update = Counter::where('counter_id',$request->id)
                                            ->update([
                                                        'counter_name' => $request->counter
                                            ]);

                          if($update)
                          {

                                $json['status'] = 'success';
                                $json['msg']    = 'Successfully Update !';
                                activity_log(get_module_id('section'), 'Alter', $request->id, 'Successfully update data !');
                          }
                    }
                    else
                    {
                        $json['status'] = 'failed';
                        $json['msg']    = 'Data failed to be stored , data '.$request->counter.' is available in the database !';
                        activity_log(get_module_id('counter'), 'Create', $request->counter, 'Data failed to stored, available in the database ! ');
                    }
                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data failed to be stored , data '.$request->counter.' data not found in the database !';
                    activity_log(get_module_id('counter'), 'Alter', $request->counter, 'Data failed to be stored, data not found in the database  !');
                }
          }

          return response()->json($json);
      }

      public function delete(Request $request)
      {
          $count = Counter::where('counter_id',$request->id)->count();
          if($count==1)
          {
                $delete = Counter::where('counter_id',$request->id)->delete();
                if($delete)
                {
                      $json['status'] = 'success';
                      $json['msg']    = 'Data Successfully Deleted !';
                      activity_log(get_module_id('counter'), 'Drop', $request->id, 'Successfully deleted data !');
                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data failed Deleted !';
                    activity_log(get_module_id('counter'), 'Drop', $request->id, 'Data failed deleted !');
                }
          }
          else
          {
                $json['status'] = 'failed';
                $json['msg']    = 'Data not found !';
                activity_log(get_module_id('counter'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
          }

          return response()->json($json);
      }
}
