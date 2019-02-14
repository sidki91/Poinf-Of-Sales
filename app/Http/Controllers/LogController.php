<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index()
    {
          if(access_level_user('view','log')=='allow')
          {
                
                return view('role/log');
          }
          else
          {
              activity_log(get_module_id('log'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }
    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $description = $request->description;
            $per_page = 10;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = DB::table('log_activity')
                      ->join('module', 'log_activity.module_id', '=', 'module.modid')
                      ->join('users', 'log_activity.uid_log', '=', 'users.id')
                      ->when($request->user_id, function($query) use ($request){
              return $query->where('user.id','like','%'.$request->user_id.'%');
                      })
                      ->count();

           $data = DB::table('log_activity')
                      ->join('module', 'log_activity.module_id', '=', 'module.modid')
                      ->join('users', 'log_activity.uid_log', '=', 'users.id')
                      ->select('log_activity.*', 'users.name','module.modid','module.modules')
                      ->when($request->user_id, function($query) use ($request){
              return $query->where('user.id','like','%'.$request->user_id.'%');
                      })
                      ->offset($start)
                      ->limit($per_page)
                      ->orderBy('log_activity.log_date','DESC')
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
            $returnHTML   = view('role.log_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }
}
