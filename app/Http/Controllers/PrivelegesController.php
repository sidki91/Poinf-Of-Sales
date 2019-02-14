<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use App\Models\Priveleges;
use Validator;
use Response;
use View;
use Auth;

class PrivelegesController extends Controller
{
    protected $rules =
    [
       'level' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

        if(access_level_user('view','priveleges')=='allow')
        {
            activity_log(get_module_id('priveleges'), 'View', '', '');
            return view('role/priveleges');
        }
        else
        {
            activity_log(get_module_id('priveleges'), 'View', '', 'Error 403 : Forbidden');
            abort(403);
        }
    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $per_page = 10;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = DB::table('user_group')
                      ->when($request->level, function($query) use ($request){
              return $query->where('user_group.gname','like','%'.$request->level.'%');
                      })
                       ->whereNull('user_group.deleted_at')
                      ->count();

           $data = DB::table('user_group')
                      ->when($request->level, function($query) use ($request){
              return $query->where('user_group.gname','like','%'.$request->level.'%');
                      })
                      ->whereNull('user_group.deleted_at')
                      ->offset($start)
                      ->limit($per_page)
                      ->orderBy('group_id','DESC')
                      ->get();

            $numPage = ceil($totalData / $per_page);
            $page       = $page;
            $perpage    = $per_page;
            $count      = $totalData;

            $array =
            [
              'page'    => $page,
              'perpage' => $perpage,
              'count'   => $count,
              'action'  => $request->key
            ];
            $returnHTML   = view('role.priveleges_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }

    public function add()
    {
        $module = Module::all();
        $returnHTML = view('role.priveleges_add',['module' => $module])->render();
        return response()->json(['status' => 'success','html' => $returnHTML]);
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
            $view      = $request->view;
            $create    = $request->create;
            $alter     = $request->alter;
            $drop      = $request->drop;

            $explode_view = explode(" ",$view);
            $implode_view = implode(",",$explode_view);
            $splite_view  = substr($implode_view, 0, -1);

            $explode_create = explode(" ",$create);
            $implode_create = implode(",",$explode_create);
            $splite_create  = substr($implode_create, 0, -1);

            $explode_alter = explode(" ",$alter);
            $implode_alter = implode(",",$explode_alter);
            $splite_alter  = substr($implode_alter, 0, -1);

            $explode_drop = explode(" ",$drop);
            $implode_drop = implode(",",$explode_drop);
            $splite_drop  = substr($implode_drop, 0, -1);

            $count = Priveleges::where('gname',$request->level)->count();
            if($count==0)
            {
                $insert = Priveleges::create([
                          'gname'       => $request->level,
                          'role_view'   => $splite_view,
            					    'role_create' => $splite_create,
            					    'role_alter'  => $splite_alter,
            			    	  'role_drop'   => $splite_drop
                        ]);
                if($insert)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Successfully added Post!';
                    activity_log(get_module_id('priveleges'), 'Create', $request->level, 'Successfully added priveleges !');
                }
            }
            else
            {
              $json['status'] = 'failed';
              $json['msg']    = 'Data failed to be stored , data '.$request->level.' is available in the database !';
              activity_log(get_module_id('priveleges'), 'Create', $request->level, 'Priveleges data failed to stored, available in the database ! ');
            }
        }

        return response()->json($json);
    }

    public function edit(Request $request)
    {
        $count =  Priveleges::where('group_id',$request->id)->count();
        if($count==1)
        {
            $row        = Priveleges::where('group_id',$request->id)->first();
            $role_view 	= $row->role_view;
            $role_create= $row->role_create;
            $role_alter	= $row->role_alter;
            $role_drop 	= $row->role_drop;
            $module     = Module::all();
            $returnHTML = View::make('role.priveleges_edit', compact('row','module','role_view','role_create','role_alter','role_drop'))->render();
            $status     = 'success';
            $msg        = '';
        }
        else
        {
            $status     = 'failed';
            $msg        = 'Sorry, data not found !';
            $returnHTML = '';
            activity_log(get_module_id('priveleges'), 'Alter', $request->id, 'Data failed to be changed, data not found !');
        }

        return response()->json(['status' => $status,'html' => $returnHTML,'msg' =>$msg]);
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
            $view      = $request->view;
            $create    = $request->create;
            $alter     = $request->alter;
            $drop      = $request->drop;

            $explode_view = explode(" ",$view);
            $implode_view = implode(",",$explode_view);
            $splite_view  = substr($implode_view, 0, -1);

            $explode_create = explode(" ",$create);
            $implode_create = implode(",",$explode_create);
            $splite_create  = substr($implode_create, 0, -1);

            $explode_alter = explode(" ",$alter);
            $implode_alter = implode(",",$explode_alter);
            $splite_alter  = substr($implode_alter, 0, -1);

            $explode_drop = explode(" ",$drop);
            $implode_drop = implode(",",$explode_drop);
            $splite_drop  = substr($implode_drop, 0, -1);

            $count = Priveleges::where('group_id',$request->id)->count();
            if($count==1)
            {
                $insert = Priveleges::where('group_id',$request->id)->update([
                          'gname'       => $request->level,
                          'role_view'   => $splite_view,
            					    'role_create' => $splite_create,
            					    'role_alter'  => $splite_alter,
            			    	  'role_drop'   => $splite_drop
                        ]);
                if($insert)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Successfully Updated Post!';
                    activity_log(get_module_id('priveleges'), 'Alter', $request->id, 'Successfully update data !');
                }
            }
            else
            {
              $json['status'] = 'failed';
              $json['msg']    = 'Data failed to be stored , data '.$request->level.' data not found in the database !';
              activity_log(get_module_id('priveleges'), 'Alter', $request->level, 'Data failed to be stored, data not found in the database  !');
            }
        }

        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count = Priveleges::where('group_id',$request->id)->count();
        if($count==1)
        {
            $delete = Priveleges::where('group_id',$request->id)->delete();
            if($delete)
            {
                $json['status'] = 'success';
                $json['msg']    = 'Data Successfully Deleted !';
                activity_log(get_module_id('priveleges'), 'Drop', $request->id, 'Successfully deleted data !');
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed Deleted !';
                activity_log(get_module_id('priveleges'), 'Drop', $request->id, 'Data failed deleted !');
            }
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data not found !';
            activity_log(get_module_id('priveleges'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
        }

        return response()->json($json);
    }
}
