<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UOM;
use Validator;
use Response;
use View;
use Auth;

class UOMController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  protected $rules =
  [
        'uom_name' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
  ];

    public function index()
    {

       if(access_level_user('view','uom')=='allow')
       {
              activity_log(get_module_id('uom'), 'View', '', '');
              return view('master/uom/index');
       }
       else
       {
           activity_log(get_module_id('uom'), 'View', '', 'Error 403 : Forbidden');
           abort(403);
       }
    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $uom  = $request->uom;
            $per_page = 10;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = UOM::with('user')
                                  ->when($request->uom, function($query) use ($request)
                                  {
                                            return $query->where('uom_name','like','%'.$request->uom.'%');
                                  })
                                  ->whereNull('deleted_at')
                                  ->count();

            $data = UOM::with('user')
                              ->when($request->uom, function($query) use ($request)
                              {
                                  return $query->where('uom_name','like','%'.$request->uom.'%');
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
            $returnHTML   = view('master.uom.uom_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }

    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(),$this->rules);
        if($validator->fails())
        {
            $json['status'] = 'error';
            $json['errors']  = $validator->getMessageBag()->toArray();
            return response()->json($json);
        }
        else
        {
            $count = UOM::where('uom_name',$request->uom_name)->get()->count();
            if($count==0)
            {
              $insert = UOM::Create(['uom_name'   => $request->uom_name,
                                     'created_by' => Auth::user()->id
                                   ]);
              if($insert)
              {
                  $json['status'] = 'success';
                  $json['msg']    = 'Successfully added Post !';
                  activity_log(get_module_id('uom'), 'Create', $request->uom_name, 'Successfully added  !');
              }


            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->uom_name.' is available in the database !';
                activity_log(get_module_id('uom'), 'Create', $request->uom_name, 'Data failed to stored, available in the database ! ');

            }
        }

        return response()->json($json);
    }

    public function edit(Request $request)
    {
        $count  = UOM::where('id',$request->id)->get()->count();
        if($count ==1)
        {
            $row        = UOM::find($request->id);
            $returnHTML = view('master.uom.edit',['row'=>$row])->render();
            $status     = 'success';
            $msg        = '';
        }
        else
        {
            $status     ='failed';
            $returnHTML = '';
            $msg        = 'Data failed to be changed, data not found !';
            activity_log(get_module_id('uom'), 'Alter', $request->id, 'Data failed to be changed, data not found !');
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
          $count = UOM::where('id',$request->id)->count();
          if($count==1)
          {
              $update = UOM::where('id',$request->id)->Update(['uom_name' => $request->uom_name]);
              if($update)
              {
                $json['status'] = 'success';
                $json['msg']    = 'Successfully Update !';
                activity_log(get_module_id('uom'), 'Alter', $request->id, 'Successfully update data !');
              }
          }
          else
          {
              $json['status'] = 'failed';
              $json['msg']    = 'Data failed to be stored , data '.$request->uom_name.' data not found in the database !';
              activity_log(get_module_id('uom'), 'Alter', $request->uom_name, 'Data failed to be stored, data not found in the database  !');
          }
        }

        return response()->json($json);
    }

    public function delete (Request $request)
    {
      $count = UOM::where('id',$request->id)->count();
      if($count==1)
      {
            $delete = UOM::where('id',$request->id)->delete();
            if($delete)
            {
                $json['status'] = 'success';
                $json['msg']    = 'Data Successfully Deleted !';
                activity_log(get_module_id('uom'), 'Drop', $request->id, 'Successfully deleted data !');
            }
            else
            {
              $json['status'] = 'failed';
              $json['msg']    = 'Data failed Deleted !';
              activity_log(get_module_id('uom'), 'Drop', $request->id, 'Data failed deleted !');
            }
      }
      else
      {
            $json['status'] = 'failed';
            $json['msg']    = 'Data not found !';
            activity_log(get_module_id('uom'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
      }

      return response()->json($json);
    }

}
