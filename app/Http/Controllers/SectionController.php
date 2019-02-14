<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Section;
use Validator;
use Response;
use View;
use Auth;

class SectionController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [
              'section' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
      ];


      public function index()
      {
            if(access_level_user('view','section')=='allow')
            {
                  activity_log(get_module_id('section'), 'View', '', '');
                  return view('master/section/index');
            }
            else
            {
                activity_log(get_module_id('section'), 'View', '', 'Error 403 : Forbidden');
                abort(403);
            }

      }

      public function list_data(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
            	if ($page != 1) $start = ($page-1) * $per_page;
            	else $start=0;

              $totalData = Section::with('user')
                                    ->when($request->section, function($query) use ($request)
                                    {
                                              return $query->where('section_name','like','%'.$request->section.'%');
                                    })
                                    ->whereNull('deleted_at')
                                    ->count();

              $data = Section::with('user')
                                ->when($request->section, function($query) use ($request)
                                {
                                    return $query->where('section_name','like','%'.$request->section.'%');
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
              $returnHTML   = view('master.section.section_list',['data' => $data,'array' => $array])->render();
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
              $count = Section::where('section_name',$request->section)->count();
              if($count==0)
              {
                    $section_code = autonumber('section','section_code','S');
                    $insert = Section::create([
                                                'section_code' => $section_code,
                                                'section_name' => $request->section,
                                                'created_by'   => Auth::user()->id
                                             ]);
                    if($insert)
                    {

                          $json['status'] = 'success';
                          $json['msg']    = 'Successfully added Post !';
                          activity_log(get_module_id('section'), 'Create', $request->section, 'Successfully added  !');

                    }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->section.' is available in the database !';
                  activity_log(get_module_id('section'), 'Create', $request->section, 'Data failed to stored, available in the database ! ');

              }
          }

          return response()->json($json);
      }

      public function edit(Request $request)
      {
          $count = Section::where('section_code',$request->id)->count();
          if($count==1)
          {
              $status     = 'success';
              $row        = Section::where('section_code',$request->id)->first();
              $returnHTML = view('master.section.edit')->with('row',$row)->render();
              $msg        = '';
          }
          else
          {
              $status      = 'failed';
              $returnHTML  = '';
              $msg         = 'Data failed to be changed, data not found !';
              activity_log(get_module_id('section'), 'Alter', $request->id, 'Data failed to be changed, data not found !');

          }

          return response()->json(['status' => $status,'html' =>$returnHTML,'msg' => $msg]);
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
              $count = Section::where('section_code',$request->id)->count();
              if($count==1)
              {
                    $count_2 = Section::where('section_name',$request->section)->count();
                    if($count_2==0)
                    {
                          $update = Section::where('section_code',$request->id)
                                             ->update([
                                                        'section_name' => $request->section,
                                                        'updated_by'   => Auth::user()->id
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
                        $json['msg']    = 'Data failed to be stored , data '.$request->section.' is available in the database !';
                        activity_log(get_module_id('section'), 'Create', $request->section, 'Data failed to stored, available in the database ! ');
                    }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->section.' data not found in the database !';
                  activity_log(get_module_id('section'), 'Alter', $request->section, 'Data failed to be stored, data not found in the database  !');
              }
          }

          return response()->json($json);
      }

      public function delete(Request $request)
      {
          $count = Section::where('section_code',$request->id)->count();
          if($count==1)
          {
                $delete = Section::where('section_code',$request->id)->delete();
                if($delete)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Data Successfully Deleted !';
                    activity_log(get_module_id('section'), 'Drop', $request->id, 'Successfully deleted data !');
                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data failed Deleted !';
                    activity_log(get_module_id('section'), 'Drop', $request->id, 'Data failed deleted !');
                }
          }
          else
          {
                $json['status'] = 'failed';
                $json['msg']    = 'Data not found !';
                activity_log(get_module_id('section'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
          }

          return response()->json($json);
      }

}
