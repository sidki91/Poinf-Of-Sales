<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse;
use Validator;
use Response;
use View;
use Auth;

class WarehouseController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  protected $rules =
  [
    'wh_name' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i'
  ];
    public function index()
    {

        if(access_level_user('view','warehouse')=='allow')
        {
              activity_log(get_module_id('warehouse'), 'View', '', '');
              return view('master/warehouse/index');
        }
        else
        {
            activity_log(get_module_id('warehouse'), 'View', '', 'Error 403 : Forbidden');
            abort(403);
        }
    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $per_page = 10;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = Warehouse::with('user')
                                  ->when($request->warehouse, function($query) use ($request)
                                  {
                                            return $query->where('wh_description','like','%'.$request->warehouse.'%');
                                  })
                                  ->whereNull('deleted_at')
                                  ->count();

            $data = Warehouse::with('user')
                              ->when($request->warehouse, function($query) use ($request)
                              {
                                  return $query->where('wh_description','like','%'.$request->warehouse.'%');
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
              'count'   => $count
            ];
            $returnHTML   = view('master.warehouse.warehouse_list',['data' => $data,'array' => $array])->render();
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
          $count = Warehouse::where('wh_description',$request->wh_name)->count();
          if($count==0)
          {
                $wh_code= autonumber('warehouse','wh_code','WH');
                $insert = Warehouse::Create([
                                              'wh_code'        => $wh_code,
                                              'wh_description' => $request->wh_name,
                                              'created_by'     => Auth::user()->id
                                            ]);
                if($insert)
                {
                      $json['status'] = 'success';
                      $json['msg']    = 'Successfully added Post!';
                      activity_log(get_module_id('warehouse'), 'Create', $request->wh_name, 'Successfully added  !');
                }

          }
          else
          {

              $json['status'] = 'failed';
              $json['msg']    = 'Data failed to be stored , data '.$request->wh_name.' is available in the database !';
              activity_log(get_module_id('warehouse'), 'Create', $request->wh_name, 'Data failed to stored, available in the database ! ');
          }
      }

          return response()->json($json);
    }

    public function edit(Request $request)
    {
       $count = Warehouse::where('id',$request->id)->count();
       if($count ==1)
       {
           $row        = Warehouse::find($request->id);
           $returnHTML = view('master.warehouse.edit',['row' => $row])->render();
           $status     = 'success';
           $msg        = '';
       }
       else
       {
          $status      = 'failed';
          $returnHTML  = '';
          $msg        = 'Data failed to be changed, data not found !';
          activity_log(get_module_id('warehouse'), 'Alter', $request->id, 'Data failed to be changed, data not found !');
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
            $count = Warehouse::where('id',$request->id)->count();
            if($count==1)
            {
                $update = Warehouse::where('id',$request->id)
                                    ->update([
                                        'wh_description' => $request->wh_name
                                    ]);
                if($update)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Successfully Updated Post!';
                    activity_log(get_module_id('warehouse'), 'Alter', $request->id, 'Successfully update data !');
                }
            }
            else
            {
              $json['status'] = 'failed';
              $json['msg']    = 'Data failed to be stored , data '.$request->wh_name.' data not found in the database !';
              activity_log(get_module_id('warehouse'), 'Alter', $request->wh_name, 'Data failed to be stored, data not found in the database  !');
            }
        }

        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count = Warehouse::where('id',$request->id)->count();
        if($count==1)
        {
              $delete = Warehouse::where('id',$request->id)->delete();
              if($delete)
              {
                  $json['status'] = 'success';
                  $json['msg']    = 'Data Successfully Deleted !';
                  activity_log(get_module_id('warehouse'), 'Drop', $request->id, 'Successfully deleted data !');
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed Deleted !';
                  activity_log(get_module_id('warehouse'), 'Drop', $request->id, 'Data failed deleted !');
              }
        }
        else
        {
          $json['status'] = 'failed';
          $json['msg']    = 'Data not found !';
          activity_log(get_module_id('warehouse'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
        }

        return response()->json($json);
    }
}
