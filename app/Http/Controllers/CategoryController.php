<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
#use Ramsey\Uuid\Uuid;
use Validator;
use Response;
use View;
use Auth;
use Browser;


class CategoryController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');

      # Penggunaan ID Generate
      #$user->id       = Uuid::uuid4()->getHex(); // toString();
  }

  protected $rules =
    [
        'description' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
    ];
    public function index()
    {

      if(access_level_user('view','product_category')=='allow')
      {
            activity_log(get_module_id('product_category'), 'View', '', '');
            return view('master/category/index');
      }
      else
      {
          activity_log(get_module_id('product_category'), 'View', '', 'Error 403 : Forbidden');
          abort(403);
      }
    }

    public function list_data(Request $request)
    {

      $page = $request->page;
      $per_page = 10;
      if ($page != 1) $start = ($page-1) * $per_page;
      else $start=0;

      $totalData = Category::with('user')
                            ->when($request->description, function($query) use ($request)
                            {
                                      return $query->where('description','like','%'.$request->description.'%');
                            })
                            ->whereNull('deleted_at')
                            ->count();

      $data = Category::with('user')
                        ->when($request->description, function($query) use ($request)
                        {
                            return $query->where('description','like','%'.$request->description.'%');
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
            $returnHTML   = view('master.category.category_list',['data' => $data,'array' => $array])->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML,'numPage'=>$numPage,'numitem' => $count) );
    }

    public function create()
    {
      return view('master/category/create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(), $this->rules);
        if ($validator->fails())
        {
          $json['status'] = 'error';
          $json['errors']  = $validator->getMessageBag()->toArray();
          return response()->json($json);
        }
        else
        {
            $count = Category::where('description',$request->description)->get()->count();
            if($count==0)
            {
                  $category = new Category();
                  $category->description = $request->description;
                  $category->created_by  = Auth::user()->id;
                  $category->save();
                  if($category)
                  {
                      $json['status'] = 'success';
                      $json['msg']    = 'Successfully added Post!';
                      activity_log(get_module_id('product_category'), 'Create', $request->description, 'Successfully added  !');
                  }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->description.' is available in the database !';
                activity_log(get_module_id('product_category'), 'Create', $request->description, 'Data failed to stored, available in the database ! ');

            }


            return response()->json($json);
        }
    }

    public function edit(Request $request)
    {
      $count = Category::where('id',$request->id)->get()->count();
      if($count==1)
      {
          $row        = Category::find($request->id);
          $returnHTML = view('master.category.edit',['row' => $row])->render();
          $status     = 'success';
          $msg        = '';
      }
      else
      {
          $status     = 'failed';
          $returnHTML = '';
          $msg        = 'Data failed to be changed, data not found !';
          activity_log(get_module_id('product_category'), 'Alter', $request->id, 'Data failed to be changed, data not found !');
      }
        return response()->json(['status' =>$status,'html' => $returnHTML,'msg' => $msg]);
    }


    public function update(Request $request)
    {
        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->fails())
        {
          $json['status'] = 'error';
          $json['errors']  = $validator->getMessageBag()->toArray();
          return response()->json($json);
        }
        else
        {
            $count = Category::where('id',$request->id)->count();

            if($count==1)
            {
                  $category = Category::where('id',$request->id)
                                        ->update(['description' => $request->description]);

                  if($category)
                  {
                    $json['status'] = 'success';
                    $json['msg']    = 'Successfully Update !';
                    activity_log(get_module_id('product_category'), 'Alter', $request->id, 'Successfully update data !');
                  }
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->description.' data not found in the database !';
                activity_log(get_module_id('priveleges'), 'Alter', $request->description, 'Data failed to be stored, data not found in the database  !');
            }


            return response()->json($json);

        }

    }

    function delete(Request $request)
    {
        $count = Category::where('id',$request->id)->get()->count();
        if($count==1)
        {
            $delete = Category::where('id',$request->id)->delete();
            if($delete)
            {
                $json['status'] = 'success';
                $json['msg']    = 'Data Successfully Deleted !';
                activity_log(get_module_id('product_category'), 'Drop', $request->id, 'Successfully deleted data !');
            }
            else
            {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed Deleted !';
                activity_log(get_module_id('product_category'), 'Drop', $request->id, 'Data failed deleted !');
            }
        }
        else
        {
          $json['status'] = 'failed';
          $json['msg']    = 'Data not found !';
          activity_log(get_module_id('product_category'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
        }

        return response()->json($json);
    }
}
