<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use Validator;
use Response;
use View;
use Auth;

class ModuleController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  protected $rules =
  [
      'modid'   => 'required|numeric|digits:11',
      'modules' => 'required|Regex:/^[\D]+$/i|max:100',
      'alias'   => 'required|Regex:/^[\D]+$/i|max:100',
  ];

    public function index()
    {
        if(access_level_user('view','module')=='allow')
        {
            activity_log(get_module_id('module'), 'View', '', '');
            return view('role/module');
        }
        else
        {
            activity_log(get_module_id('module'), 'View', '', 'Error 403 : Forbidden');
            abort(403);
        }

    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $per_page = 10;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = DB::table('module')
                      ->join('users', 'users.id', '=', 'module.created_by')
                      ->when($request->module, function($query) use ($request){
              return $query->where('module.modules','like','%'.$request->module.'%');
                      })
                       ->whereNull('module.deleted_at')
                      ->count();

           $data = DB::table('module')
                      ->join('users', 'users.id', '=', 'module.created_by')
                      ->select('module.*', 'users.name')
                      ->when($request->module, function($query) use ($request){
              return $query->where('module.modules','like','%'.$request->module.'%');
                      })
                      ->whereNull('module.deleted_at')
                      ->offset($start)
                      ->limit($per_page)
                      ->orderBy('module.parent','ASC')
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
            $returnHTML   = view('role.module_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }

    public function add(Request $request)
    {
        $returnHTML = view('role.module_add')->render();
        $status     = 'success';
        return response()->json(['status' =>$status,'html' => $returnHTML]);
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
            $count = Module::where('modid',$request->modid)->count();
            if($count==0)
            {
                $insert = Module::create([
                                          'modid'      => $request->modid,
                                          'modules'    => $request->modules,
                                          'alias'      => $request->alias,
                                          'parent'     => Module::max('parent')+1,
                                          'enable'     => $request->view,
                                          'r_create'   => $request->create,
                                          'r_alter'    => $request->alter,
                                          'r_drop'     => $request->drop,
                                          'created_by' => Auth::user()->id
                                        ]);
                if($insert)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Successfully added Post!';
                    activity_log(get_module_id('module'), 'Create', $request->modules, 'Successfully added priveleges !');
                }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->modules.' is available in the database !';
                activity_log(get_module_id('module'), 'Create', $request->modules, 'Priveleges data failed to stored, available in the database ! ');
            }

        }

        return response()->json($json);
    }

    public function edit(Request $request)
    {
        $count = Module::where('modid',$request->id)->count();
        if($count==1)
        {
            $status     = 'success';
            $row        = Module::where('modid',$request->id)->first();
            $returnHTML = view('role.module_edit',['row' => $row])->render();
            $msg        = '';
        }
        else
        {
            $status     = 'failed';
            $returnHTML = '';
            $msg        = 'Data failed to be changed, data not found !';
            activity_log(get_module_id('module'), 'Alter', $request->id, 'Data failed to be changed, data not found !');
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
            $count = Module::where('modid',$request->id)->count();
            if($count==1)
            {

                      $update = Module::where('modid',$request->id)->update([
                                        'modid'    => $request->modid,
                                        'modules'  => $request->modules,
                                        'alias'    => $request->alias,
                                        'parent'   => $request->parent,
                                        'enable'   => $request->view,
                                        'r_create' => $request->create,
                                        'r_alter'  => $request->alter,
                                        'r_drop'   => $request->drop
                      ]);
                      if($update)
                      {
                          $json['status'] = 'success';
                          $json['msg']    = 'Successfully Updated Post!';
                          activity_log(get_module_id('module'), 'Alter', $request->id, 'Successfully update data !');
                      }
                }
                else
                {

                    $json['status'] = 'failed';
                    $json['msg']    = 'Data failed to be stored , data '.$request->modules.' data not found in the database !';
                    activity_log(get_module_id('module'), 'Alter', $request->modules, 'Data failed to be stored, data not found in the database  !');

                }

        }

        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count = Module::where('modid',$request->id)->count();
        if($count==1)
        {
            $delete = Module::where('modid',$request->id)->delete();
            if($delete)
            {
                $json['status'] = 'success';
                $json['msg']    = 'Data Successfully Deleted !';
                activity_log(get_module_id('module'), 'Drop', $request->id, 'Successfully deleted data !');
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed Deleted !';
                activity_log(get_module_id('module'), 'Drop', $request->id, 'Data failed deleted !');
            }
        }
        else
        {
            $json['status'] = 'failed';
            $json['msg']    = 'Data not found !';
            activity_log(get_module_id('module'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');

        }

        return response()->json($json);
    }

}
