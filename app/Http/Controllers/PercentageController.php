<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Percentage;
use Validator;
use Response;
use View;
use Auth;

class PercentageController extends Controller
{

      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [
            'percentage' => 'required|min:1|max:32|',
      ];

      public function index()
      {
            if(access_level_user('view','percentage')=='allow')
            {
                activity_log(get_module_id('percentage'), 'View', '', '');
                return view('master/percentage/index');
            }
            else
            {
                activity_log(get_module_id('percentage'), 'View', '', 'Error 403 : Forbidden');
                abort(403);
            }
      }

      public function list_data(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
            	if ($page != 1) $start = ($page-1) * $per_page;
            	else $start=0;

              $totalData = Percentage::with('user')
                                    ->when($request->percentage, function($query) use ($request)
                                    {
                                              return $query->where('percentage','like','%'.$request->percentage.'%');
                                    })
                                    ->whereNull('deleted_at')
                                    ->count();

              $data = Percentage::with('user')
                                ->when($request->percentage, function($query) use ($request)
                                {
                                    return $query->where('percentage','like','%'.$request->percentage.'%');
                                })
                                 ->whereNull('deleted_at')
                                 ->offset($start)
                                 ->limit($per_page)
                                 ->orderBy('percentage_id','ASC')
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
              $returnHTML   = view('master.percentage.percentage_list',['data' => $data,'array' => $array])->render();
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
              $count = Percentage::where('percentage',$request->percentage)->count();
              if($count==0)
              {
                    $percentage_id = autonumber('percentage','percentage_id','JP');
                    $insert = Percentage::create([
                                  'percentage_id' => $percentage_id,
                                  'percentage'    => $request->percentage,
                                  'created_by'    => Auth::user()->id
                    ]);

                    if($insert)
                    {
                          $json['status'] = 'success';
                          $json['msg']    = 'Successfully added Post !';
                          activity_log(get_module_id('percentage'), 'Create', $request->percentage, 'Successfully added  !');

                    }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->percentage.' is available in the database !';
                  activity_log(get_module_id('percentage'), 'Create', $request->percentage, 'Data failed to stored, available in the database ! ');

              }
          }

          return response()->json($json);
      }

      public function edit(Request $request)
      {
          $count = Percentage::where('percentage_id',$request->id)->count();
          if($count==1)
          {
                $row        = Percentage::where('percentage_id',$request->id)->first();
                $status     = 'success';
                $returnHTML = view('master.percentage.edit',['row' => $row])->render();
                $msg        = '';
          }
          else
          {
              $status      = 'failed';
              $returnHTML  = '';
              $msg         = 'Data failed to be changed, data not found !';
              activity_log(get_module_id('percentage'), 'Alter', $request->id, 'Data failed to be changed, data not found !');

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
              $count = Percentage::where('percentage_id',$request->id)->count();
              if($count==1)
              {
                $count_2 = Percentage::where('percentage',$request->percentage)->count();
                if($count_2==0)
                {

                      $update = Percentage::where('percentage_id',$request->id)
                                            ->update([
                                              'percentage' => $request->percentage,
                                              'updated_by' => Auth::user()->id
                                            ]);
                      if($update)
                      {

                        $json['status'] = 'success';
                        $json['msg']    = 'Successfully Update !';
                        activity_log(get_module_id('percentage'), 'Alter', $request->id, 'Successfully update data !');
                      }

                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data failed to be stored , data '.$request->percentage.' is available in the database !';
                    activity_log(get_module_id('percentage'), 'Create', $request->percentage, 'Data failed to stored, available in the database ! ');
                }

              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->percentage.' data not found in the database !';
                  activity_log(get_module_id('percentage'), 'Alter', $request->percentage, 'Data failed to be stored, data not found in the database  !');
              }
          }

          return response()->json($json);
      }

      public function delete(Request $request)
      {
          $count = Percentage::where('percentage_id',$request->id)->count();
          if($count==1)
          {
                $delete = Percentage::where('percentage_id',$request->id)->delete();
                if($delete)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Data Successfully Deleted !';
                    activity_log(get_module_id('percentage'), 'Drop', $request->id, 'Successfully deleted data !');
                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data failed Deleted !';
                    activity_log(get_module_id('percentage'), 'Drop', $request->id, 'Data failed deleted !');
                }
          }
          else
          {
                $json['status'] = 'failed';
                $json['msg']    = 'Data not found !';
                activity_log(get_module_id('percentage'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
          }

          return response()->json($json);
      }


}
