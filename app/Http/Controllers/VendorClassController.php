<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VendorClass;
use Validator;
use Response;
use View;
use Auth;

class VendorClassController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      protected $rules =
      [
        'vendor_class' => 'required|min:2|max:32|regex:/^[a-z ,.\'-]+$/i',
      ];

      public function index()
      {
            if(access_level_user('view','vendor_class')=='allow')
            {
                  activity_log(get_module_id('vendor_class'), 'View', '', '');
                  return view('master/vendorClass/index');
            }
            else
            {
                activity_log(get_module_id('vendor_class'), 'View', '', 'Error 403 : Forbidden');
                abort(403);
            }
      }

      public function list_data(Request $request)
      {
              $page = $request->page;
              $per_page = 10;
              if ($page != 1) $start = ($page-1) * $per_page;
              else $start=0;

              $totalData = VendorClass::with('user')
                                    ->when($request->vendor_class, function($query) use ($request)
                                    {
                                              return $query->where('vendor_class','like','%'.$request->vendor_class.'%');
                                    })
                                    ->whereNull('deleted_at')
                                    ->count();

              $data = VendorClass::with('user')
                                ->when($request->vendor_class, function($query) use ($request)
                                {
                                    return $query->where('vendor_class','like','%'.$request->vendor_class.'%');
                                })
                                 ->whereNull('deleted_at')
                                 ->offset($start)
                                 ->limit($per_page)
                                 ->orderBy('created_at','ASC')
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
              $returnHTML   = view('master.vendorClass.vendorclass_list',['data' => $data,'array' => $array])->render();
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
              $count = VendorClass::where('vendor_class',$request->vendor_class)->count();
              if($count==0)
              {
                    $vendor_class_id = autonumber('vendor_class','vendor_class_id','VC');
                    $insert = VendorClass::create([
                                                      'vendor_class_id' => $vendor_class_id,
                                                      'vendor_class'    => $request->vendor_class,
                                                      'created_by'      => Auth::user()->id
                                                 ]);
                    if($insert)
                    {
                        $json['status'] = 'success';
                        $json['msg']    = 'Successfully added Post !';
                        activity_log(get_module_id('vendor_class'), 'Create', $request->vendor_class, 'Successfully added  !');

                    }
              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->vendor_class.' is available in the database !';
                  activity_log(get_module_id('vendor_class'), 'Create', $request->vendor_class, 'Data failed to stored, available in the database ! ');

              }
          }

          return response()->json($json);
      }

      public function edit(Request $request)
      {
          $count = VendorClass::where('vendor_class_id',$request->id)->count();
          if($count==1)
          {
              $row            = VendorClass::where('vendor_class_id',$request->id)->first();
              $returnHTML     = view('master.vendorClass.edit')->with('row',$row)->render();
              $json['status'] = 'success';
              $json['html']   = $returnHTML;
          }
          else
          {
             $json['status']      = 'failed';
             $json['msg']         = 'Data failed to be changed, data not found !';
             activity_log(get_module_id('customer_class'), 'Alter', $request->id, 'Data failed to be changed, data not found !');

          }

          return response()->json($json);
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
              $count = VendorClass::where('vendor_class_id',$request->id)->count();
              if($count==1)
              {
                  $count_2 = VendorClass::where('vendor_class',$request->vendor_class)->count();
                  if($count_2==0)
                  {
                        $update = VendorClass::where('vendor_class_id',$request->id)
                                               ->update([
                                                          'vendor_class' => $request->vendor_class,
                                                          'updated_by'   => Auth::user()->id
                                                       ]);
                        if($update)
                        {
                            $json['status'] = 'success';
                            $json['msg']    = 'Successfully Update !';
                            activity_log(get_module_id('vendor_class'), 'Alter', $request->id, 'Successfully update data !');

                        }
                  }
                  else
                  {
                      $json['status'] = 'failed';
                      $json['msg']    = 'Data failed to be stored , data '.$request->vendor_class.' is available in the database !';
                      activity_log(get_module_id('vendor_class'), 'Create', $request->vendor_class, 'Data failed to stored, available in the database ! ');
                  }

              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed to be stored , data '.$request->vendor_class.' data not found in the database !';
                  activity_log(get_module_id('vendor_class'), 'Alter', $request->vendor_class, 'Data failed to be stored, data not found in the database  !');
              }
          }

          return response()->json($json);
      }

      public function delete(Request $request)
      {
          $count = VendorClass::where('vendor_class_id',$request->id)->count();
          if($count==1)
          {
              $delete = VendorClass::where('vendor_class_id',$request->id)->delete();
              if($delete)
              {
                    $json['status'] = 'success';
                    $json['msg']    = 'Data Successfully Deleted !';
                    activity_log(get_module_id('vendor_class'), 'Drop', $request->id, 'Successfully deleted data !');

              }
              else
              {
                  $json['status'] = 'failed';
                  $json['msg']    = 'Data failed Deleted !';
                  activity_log(get_module_id('vendor_class'), 'Drop', $request->id, 'Data failed deleted !');

              }
          }
          else
          {
                $json['status'] = 'failed';
                $json['msg']    = 'Data not found !';
                activity_log(get_module_id('vendor_class'), 'Drop', $request->id, 'Data failed deleted data not found in the database  !');
          }

          return response()->json($json);

      }
}
