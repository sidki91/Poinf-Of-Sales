<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Currency;
use Validator;
use Response;
use View;
use Auth;

class CurrencyController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  protected $rules =
  [
      'currency_name' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
  ];
    public function index()
    {
        if(access_level_user('view','currency')=='allow')
        {
              activity_log(get_module_id('currency'), 'View', '', '');
              return view('master/currency/index');
        }
        else
        {
            activity_log(get_module_id('currency'), 'View', '', 'Error 403 : Forbidden');
            abort(403);
        }
    }

    public function list_data(Request $request)
    {
            $page     = $request->page;
            $per_page = 10;
          	if ($page != 1) $start = ($page-1) * $per_page;
          	else $start=0;

            $totalData = Currency::with('user')
                                  ->when($request->currency, function($query) use ($request)
                                  {
                                            return $query->where('currency_name','like','%'.$request->currency.'%');
                                  })
                                  ->whereNull('deleted_at')
                                  ->count();

            $data = Currency::with('user')
                              ->when($request->currency, function($query) use ($request)
                              {
                                  return $query->where('currency_name','like','%'.$request->currency.'%');
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
            $returnHTML   = view('master.currency.currency_list',['data' => $data,'array' => $array])->render();
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
           $count = Currency::where('currency_name',$request->currency_name)->count();
           if($count==0)
           {
                $insert = Currency::Create([
                                            'currency_name' => $request->currency_name,
                                            'created_by'    => Auth::user()->id
                                          ]);
                if($insert)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Successfully added Post!';
                    activity_log(get_module_id('currency'), 'Create', $request->currency_name, 'Successfully added  !');
                }
           }
           else
           {
                $json['status'] = 'failed';
                $json['msg']    = 'Data failed to be stored , data '.$request->currency_name.' is available in the database !';
                activity_log(get_module_id('currency'), 'Create', $request->currency_name, 'Data failed to stored, available in the database ! ');

           }
        }

        return response()->json($json);
    }

    public function edit(Request $request)
    {
        $count = Currency::where('id',$request->id)->count();

        if($count==1)
        {
            $row        = Currency::find($request->id);
            $returnHTML = view('master.currency.edit',['row' => $row])->render();
            $status     = 'success';
            $msg        = '';
        }
        else
        {
            $status     = 'failed';
            $returnHTML = '';
            $msg        = 'Data failed to be changed, data not found !';
            activity_log(get_module_id('currency'), 'Alter', $request->id, 'Data failed to be changed, data not found !');
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
            $count = Currency::where('id',$request->id)->count();
            if($count==1)
            {
                $update = Currency::where('id',$request->id)
                                    ->update([
                                              'currency_name' => $request->currency_name
                                            ]);
                if($update)
                {
                    $json['status'] = 'success';
                    $json['msg']    = 'Successfully Update !';
                    activity_log(get_module_id('currency'), 'Alter', $request->id, 'Successfully update data !');
                }
            }
            else
            {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->currency_name.' data not found in the database !';
                  activity_log(get_module_id('currency'), 'Alter', $request->currency_name, 'Data failed to be stored, data not found in the database  !');
            }

        }

        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $count = Currency::where('id',$request->id)->count();
        if($count==1)
        {
             $action = Currency::where('id',$request->id)->delete();
             if($action)
             {
                  $json['status'] = 'success';
                  $json['msg']    = 'Data Successfully Deleted !';
                  activity_log(get_module_id('currency'), 'Drop', $request->id, 'Successfully deleted data !');
             }
             else
             {
                 $json['status'] = 'failed';
                 $json['msg']    = 'Data failed Deleted !';
                 activity_log(get_module_id('currency'), 'Drop', $request->id, 'Data failed deleted !');
             }
        }
        else
        {
          $json['status'] = 'failed';
          $json['msg']    = 'Data not found !';
          activity_log(get_module_id('currency'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
        }


        return response()->json($json);
    }



}
