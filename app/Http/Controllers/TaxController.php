<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tax;
use Validator;
Use Response;
use View;
Use Auth;


class TaxController extends Controller
{

        public function __construct()
        {
            $this->middleware('auth');
        }

        protected $rules =
        [
          'tax_name'     => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
          'tax_value'    => 'required|numeric',
        ];

        public function index()
        {
              if(access_level_user('view','tax')=='allow')
              {
                     activity_log(get_module_id('tax'), 'View', '', '');
                     return view('master/tax/index');
              }
              else
              {
                  activity_log(get_module_id('tax'), 'View', '', 'Error 403 : Forbidden');
                  abort(403);
              }
        }

        public function list_data(Request $request)
        {
                $page = $request->page;
                $per_page = 10;
              	if ($page != 1) $start = ($page-1) * $per_page;
              	else $start=0;

                $totalData = Tax::with('user')
                                      ->when($request->tax, function($query) use ($request)
                                      {
                                                return $query->where('tax_name','like','%'.$request->tax.'%');
                                      })
                                      ->whereNull('deleted_at')
                                      ->count();

                $data = Tax::with('user')
                                  ->when($request->tax, function($query) use ($request)
                                  {
                                      return $query->where('tax_name','like','%'.$request->tax.'%');
                                  })
                                   ->whereNull('deleted_at')
                                   ->offset($start)
                                   ->limit($per_page)
                                   ->orderBy('tax_id','ASC')
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
                $returnHTML   = view('master.tax.tax_list',['data' => $data,'array' => $array])->render();
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
                $count = Tax::where('tax_name',$request->tax_name)->count();
                if($count==0)
                {
                    $tax_id = autonumber('tax','tax_id','TD');
                    $insert = Tax::create([
                              'tax_id'     => $tax_id,
                              'tax_name'   => $request->tax_name,
                              'tax_value'  => $request->tax_value,
                              'created_by' => Auth::user()->id
                    ]);
                    if($insert)
                    {
                        $json['status'] = 'success';
                        $json['msg']    = 'Successfully added Post !';
                        activity_log(get_module_id('tax'), 'Create', $request->tax_name, 'Successfully added  !');

                    }

                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data failed to be stored , data '.$request->tax_name.' is available in the database !';
                    activity_log(get_module_id('tax'), 'Create', $request->tax_name, 'Data failed to stored, available in the database ! ');

                }
            }

            return response()->json($json);
        }

        public function edit(Request $request)
        {
            $count = Tax::where('tax_id',$request->id)->count();
            if($count==1)
            {
                $status     = 'success';
                $msg        = '';
                $row        = Tax::where('tax_id',$request->id)->first();
                $returnHTML = view('master.tax.edit',['row' => $row])->render();
            }
            else
            {
                $status      = 'failed';
                $returnHTML  = '';
                $msg         = 'Data failed to be changed, data not found !';
                activity_log(get_module_id('tax'), 'Alter', $request->id, 'Data failed to be changed, data not found !');

            }

            return response()->json(['status' => $status,'html' => $returnHTML,'msg' => $msg]);
        }

        public function update(Request $request)
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
                $count = Tax::where('tax_id',$request->id)->count();
                if($count ==1)
                {
                      $count_2 = Tax::where('tax_name',$request->tax_name)->count();
                      if($count_2==0)
                      {
                          $update = Tax::where('tax_id',$request->id)
                                         ->update([
                                           'tax_name'  => $request->tax_name,
                                           'tax_value' => $request->tax_value,
                                           'updated_by'=> Auth::user()->id
                                         ]);
                          if($update)
                          {
                              $json['status'] = 'success';
                              $json['msg']    = 'Successfully Update !';
                              activity_log(get_module_id('tax'), 'Alter', $request->id, 'Successfully update data !');

                          }

                      }
                      else
                      {
                          $json['status'] = 'failed';
                          $json['msg']    = 'Data failed to be stored , data '.$request->tax_name.' is available in the database !';
                          activity_log(get_module_id('tax'), 'Create', $request->tax_name, 'Data failed to stored, available in the database ! ');
                      }

                }
                else
                {
                    $json['status'] = 'failed';
                    $json['msg']    = 'Data failed to be stored , data '.$request->tax_name.' data not found in the database !';
                    activity_log(get_module_id('tax'), 'Alter', $request->tax_name, 'Data failed to be stored, data not found in the database  !');
                }
            }

            return response()->json($json);
        }

        public function delete(Request $request)
        {
            $count = Tax::where('tax_id',$request->id)->count();
            if($count==1)
            {
                  $delete = Tax::where('tax_id',$request->id)->delete();
                  if($delete)
                  {
                      $json['status'] = 'success';
                      $json['msg']    = 'Data Successfully Deleted !';
                      activity_log(get_module_id('tax'), 'Drop', $request->id, 'Successfully deleted data !');

                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data failed Deleted !';
                      activity_log(get_module_id('tax'), 'Drop', $request->id, 'Data failed deleted !');
                  }
            }
            else
            {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data not found !';
                  activity_log(get_module_id('tax'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
            }

            return response()->json($json);
        }
}
