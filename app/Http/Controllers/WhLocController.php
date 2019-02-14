<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WhLoc;
use App\Models\Warehouse;
use Validator;
use Response;
use View;
use Auth;

class WhLocController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }
      protected $rules =
      [
        'warehouse_location' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i'
      ];

      public function index()
      {
            if(access_level_user('view','warehouse_location')=='allow')
            {
                  activity_log(get_module_id('warehouse_location'), 'View', '', '');
                  return view('master/whLoc/index');
            }
            else
            {
                activity_log(get_module_id('warehouse_location'), 'View', '', 'Error 403 : Forbidden');
                abort(403);
            }
      }

      public function list_data(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
            	if ($page != 1) $start = ($page-1) * $per_page;
            	else $start=0;

              $totalData = WhLoc::with('user','warehouse')
                                    ->when($request->warehouse_loc, function($query) use ($request)
                                    {
                                              return $query->where('location','like','%'.$request->warehouse_loc.'%');
                                    })
                                    ->whereNull('deleted_at')
                                    ->count();

              $data = WhLoc::with('user','warehouse')
                                ->when($request->warehouse_loc, function($query) use ($request)
                                {
                                    return $query->where('location','like','%'.$request->warehouse_loc.'%');
                                })
                                 ->whereNull('deleted_at')
                                 ->offset($start)
                                 ->limit($per_page)
                                 ->orderBy('wh_loc_id','ASC')
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
              $returnHTML   = view('master.whLoc.whLoc_list',['data' => $data,'array' => $array])->render();
              return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
      }

      public function add(Request $request)
      {
          $warehouse  = Warehouse::all();
          $returnHTML = view('master.whLoc.add',['warehouse' => $warehouse])->render();
          return response()->json(['status' =>'success','html' => $returnHTML]);
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
              $count = WhLoc::where('location',$request->warehouse_location)->count();
              if($count==0)
              {
                  $wh_loc_id = autonumber('warehouse_location','wh_loc_id','WHL');
                  $insert = WhLoc::create([
                                            'wh_loc_id'  => $wh_loc_id,
                                            'wh_id'      => $request->wh_id,
                                            'location'   => $request->warehouse_location,
                                            'created_by' => Auth::user()->id
                                          ]);
                  if($insert)
                  {
                      $json['status'] = 'success';
                      $json['msg']    = 'Successfully added Post!';
                      activity_log(get_module_id('warehouse_location'), 'Create', $request->warehouse_location, 'Successfully added  !');

                  }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->warehouse_location.' is available in the database !';
                  activity_log(get_module_id('warehouse_location'), 'Create', $request->warehouse_location, 'Data failed to stored, available in the database ! ');

              }
          }

          return response()->json($json);
      }

      public function edit(Request $request)
      {
          $count = WhLoc::where('wh_loc_id',$request->id)->count();
          if($count==1)
          {
              $row        = WhLoc::where('wh_loc_id',$request->id)->First();
              $warehouse  = Warehouse::all();
              $returnHTML = View::make('master.whLoc.edit', compact('row','warehouse'))->render();
              $status     = 'success';
              $msg        = '';
          }
          else
          {
              $status     = 'failed';
              $returnHTML = '';
              $msg        = 'Data failed to be changed, data not found !';
              activity_log(get_module_id('warehouse_location'), 'Alter', $request->id, 'Data failed to be changed, data not found !');
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
              $count = WhLoc::where('wh_loc_id',$request->id)->count();
              if($count ==1)
              {
                  $update = whLoc::where('wh_loc_id',$request->id)
                  ->update([
                          'wh_id'      => $request->wh_id,
                          'location'   => $request->warehouse_location,
                          'updated_by' => Auth::user()->id
                  ]);
                  if($update)
                  {
                      $json['status'] = 'success';
                      $json['msg']    = 'Successfully Updated Post!';
                      activity_log(get_module_id('warehouse_location'), 'Alter', $request->id, 'Successfully update data !');
                  }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->warehouse_location.' data not found in the database !';
                  activity_log(get_module_id('warehouse_location'), 'Alter', $request->warehouse_location, 'Data failed to be stored, data not found in the database  !');
              }
          }

          return response()->json($json);
      }

      public function delete(Request $request)
      {
          $count = WhLoc::where('wh_loc_id',$request->id)->count();
          if($count==1)
          {
              $delete = WhLoc::where('wh_loc_id',$request->id)->delete();
              if($delete)
              {
                  $json['status'] = 'success';
                  $json['msg']    = 'Data Successfully Deleted !';
                  activity_log(get_module_id('warehouse_location'), 'Drop', $request->id, 'Successfully deleted data !');
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed Deleted !';
                  activity_log(get_module_id('warehouse_location'), 'Drop', $request->id, 'Data failed deleted !');
              }
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data not found !';
              activity_log(get_module_id('warehouse_location'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
          }

          return response()->json($json);
      }
}
