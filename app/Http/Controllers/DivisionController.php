<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Division;
use Validator;
use Response;
use View;
use Auth;

class DivisionController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [
              'division' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
      ];

      public function index()
      {
            if(access_level_user('view','division')=='allow')
            {
                  activity_log(get_module_id('division'), 'View', '', '');
                  return view('master/division/index');
            }
            else
            {
                activity_log(get_module_id('division'), 'View', '', 'Error 403 : Forbidden');
                abort(403);
            }
      }

      public function list_data(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
            	if ($page != 1) $start = ($page-1) * $per_page;
            	else $start=0;

              $totalData = Division::with('user')
                                    ->when($request->division, function($query) use ($request)
                                    {
                                              return $query->where('division_name','like','%'.$request->division.'%');
                                    })
                                    ->whereNull('deleted_at')
                                    ->count();

              $data = Division::with('user')
                                ->when($request->division, function($query) use ($request)
                                {
                                    return $query->where('division_name','like','%'.$request->division.'%');
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
              $returnHTML   = view('master.division.division_list',['data' => $data,'array' => $array])->render();
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
                $count = Division::where('division_name',$request->division)->count();
                if($count==0)
                {
                    $division_code = autonumber('division','division_code','D');
                    $insert = Division::create([
                                        'division_code' => $division_code,
                                        'division_name' => $request->division,
                                        'created_by'    => Auth::user()->id
                                      ]);
                    if($insert)
                    {
                        $json['status'] = 'success';
                        $json['msg']    = 'Successfully added Post !';
                        activity_log(get_module_id('division'), 'Create', $request->division, 'Successfully added  !');

                    }
                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data failed to be stored , data '.$request->division.' is available in the database !';
                    activity_log(get_module_id('division'), 'Create', $request->division, 'Data failed to stored, available in the database ! ');

                }
            }

            return response()->json($json);
      }

      public function edit(Request $request)
      {
          $count = Division::where('division_code',$request->id)->count();
          if($count==1)
          {
              $status     = 'success';
              $row        = Division::where('division_code',$request->id)->first();
              $returnHTML = view('master.division.edit',compact('row'))->render();
              $msg        = '';
          }
          else
          {
              $status      = 'failed';
              $returnHTML  = '';
              $msg         = 'Data failed to be changed, data not found !';
              activity_log(get_module_id('division'), 'Alter', $request->id, 'Data failed to be changed, data not found !');

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
              $count = Division::where('division_code',$request->id)->count();
              if($count==1)
              {
                    $count_2 = Division::where('division_name',$request->division)->count();
                    if($count_2==0)
                    {
                          $update = Division::where('division_code',$request->id)
                                            ->update([
                                                'division_name' => $request->division
                                            ]);
                          if($update)
                          {
                                $json['status'] = 'success';
                                $json['msg']    = 'Successfully Update !';
                                activity_log(get_module_id('division'), 'Alter', $request->id, 'Successfully update data !');
                          }

                    }
                    else
                    {
                        $json['status'] = 'failed';
                        $json['msg']    = 'Data failed to be stored , data '.$request->division.' is available in the database !';
                        activity_log(get_module_id('division'), 'Create', $request->division, 'Data failed to stored, available in the database ! ');
                    }

              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->division.' data not found in the database !';
                  activity_log(get_module_id('division'), 'Alter', $request->division, 'Data failed to be stored, data not found in the database  !');
              }
          }

          return response()->json($json);
      }

      public function delete(Request $request)
      {
          $count = Division::where('division_code',$request->id)->count();
          if($count==1)
          {
              $delete = Division::where('division_code',$request->id)->delete();
              if($delete)
              {
                  $json['status'] = 'success';
                  $json['msg']    = 'Data Successfully Deleted !';
                  activity_log(get_module_id('division'), 'Drop', $request->id, 'Successfully deleted data !');

              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed Deleted !';
                  activity_log(get_module_id('division'), 'Drop', $request->id, 'Data failed deleted !');
              }
          }
          else
          {
                $json['status'] = 'failed';
                $json['msg']    = 'Data not found !';
                activity_log(get_module_id('division'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
          }

          return response()->json($json);
      }


}
