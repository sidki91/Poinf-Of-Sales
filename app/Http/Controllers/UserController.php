<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Priveleges;
use App\Models\Counter;
use Validator;
use Response;
use View;
use Auth;
use Hash;

class UserController extends Controller
{
  #cek password
  // if (Hash::check('123456', $row->password))
  // {
  //     $pesan = 'sukses';
  // }else
  // {
  //   $pesan = 'gagal';
  // }
  // dd($pesan);

  public function __construct()
  {
      $this->middleware('auth');
  }
  protected $rules =
  [
      'name'     => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
      'username' => 'required|Regex:/^[\D]+$/i|max:100',
      'email'    => 'required|email|max:255|unique:users',
      'password' => 'required|min:6',
  ];

  protected $rules2 =
  [
      'name'     => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
      'username' => 'required|Regex:/^[\D]+$/i|max:100',
      'email'    => 'required|email|max:255',
  ];

    public function index()
    {

          if(access_level_user('view','user')=='allow')
          {
              activity_log(get_module_id('user'), 'View', '', '');
              return view('role.user');
          }
          else
          {
              activity_log(get_module_id('user'), 'View', '', 'Error 403 : Forbidden');
              abort(403);
          }
    }

    public function list_data(Request $request)
    {
            $page = $request->page;
            $per_page = 10;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = DB::table('users')
                      ->join('user_group', 'users.access_id', '=', 'user_group.group_id')
                      ->when($request->name_search, function($query) use ($request){
              return $query->where('users.name','like','%'.$request->name_search.'%');
                      })
                       ->whereNull('users.deleted_at')
                      ->count();

           $data = DB::table('users')
                      ->join('user_group', 'users.access_id', '=', 'user_group.group_id')
                      ->select('users.*', 'user_group.gname')
                      ->when($request->name_search, function($query) use ($request){
              return $query->where('users.name','like','%'.$request->name_search.'%');
                      })
                      ->whereNull('users.deleted_at')
                      ->offset($start)
                      ->limit($per_page)
                      ->orderBy('id','DESC')
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
            $returnHTML   = view('role.users_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );



    }

    public function add(Request $request)
    {
        $priveleges = Priveleges::all();
        $counter    = Counter::all();

        $returnHTML = view('role.add',compact('priveleges','counter'))->render();
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
            $count = User::where('username',$request->username)->count();
            if($count==0)
            {
                  $insert = User::create([
                                  'name'      => $request->name,
                                  'email'     => $request->email,
                                  'username'  => $request->username,
                                  'password'  => Hash::make($request->password),
                                  'access_id' => $request->priveleges
                  ]);
                  if($insert)
                  {
                        $json['status'] = 'success';
                        $json['msg']    = 'Successfully added Post!';
                        activity_log(get_module_id('user'), 'Create', $request->name, 'Successfully added user ! ');
                  }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data : '.$request->name.' is available in the database !';
                activity_log(get_module_id('user'), 'Create', $request->name, 'User data failed to stored, available in the database !  ');

            }
        }

        return response()->json($json);
    }

    public function edit(Request $request)
    {
        $count = User::where('id',$request->id)->count();
        if($count==1)
        {
            $row        = User::find($request->id);
            $priveleges = Priveleges::all();
            $returnHTML = View::make('role.edit', compact('row','priveleges'))->render();
            $status     = 'success';
            $msg        = '';
        }
        else
        {
            $status     = 'failed';
            $returnHTML = '';
            $msg        = 'Data failed to be changed, data not found !';
            activity_log(get_module_id('user'), 'Alter', $request->id, 'Data failed to be changed, data not found !');
        }
        return response()->json(['status' => $status,'html' => $returnHTML,'msg' => $msg]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(Input::all(),$this->rules2);
        if($validator->fails())
        {
            $json['status'] = 'error';
            $json['errors'] = $validator->getMessageBag()->toArray();
            return response()->json($json);
        }
        else
        {
            $count = User::where('id',$request->id)->count();
            if($count==1)
            {
                $update = User::where('id',$request->id)->update([
                                'name'      => $request->name,
                                'email'     => $request->email,
                                'username'  => $request->username,
                                'access_id' => $request->priveleges
                ]);

                if($update)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Successfully Update !';
                    activity_log(get_module_id('user'), 'Alter', $request->id, 'Successfully update data !');
                }
            }
            else
            {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data : '.$request->name.' data not found in the database !';
                  activity_log(get_module_id('user'), 'Alter', $request->name, 'Data failed to be stored, data not found in the database !');
            }
        }

        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count = User::where('id',$request->id)->count();
        if($count==1)
        {
            $delete = User::where('id',$request->id)->delete();
            if($delete)
            {
                $json['status'] = 'success';
                $json['msg']    = 'Data Successfully Deleted !';
                activity_log(get_module_id('user'), 'Drop', $request->id, 'Successfully deleted data !');
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed Deleted !';
                activity_log(get_module_id('user'), 'Drop', $request->id, 'Data failed deleted !');
            }
        }
        else
        {
              $json['status'] = 'failed';
              $json['msg']    = 'Data not found !';
              activity_log(get_module_id('user'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
        }

        return response()->json($json);
    }

}
