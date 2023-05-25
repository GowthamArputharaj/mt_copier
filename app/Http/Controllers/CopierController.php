<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CopierController extends Controller
{
  public function CopierHandle(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'uid' => 'required|exists:licenses,uid',
      ]
    );

    if ($validator->fails()) {
      $message = $validator->errors()->first();
      $data = [
        'status' => 200,
        'message' => 'Invalid License.',
        'data' => "Invalid License."
      ];
      return response($data);
    }

    try { 
      $uid = $request->uid;
      $license = DB::table('licenses')->where('uid', $uid)->first();
      if(isset($license->id) == false) {
        $message = 'Invalid License.';
        $data = [
          'status' => 200,
          'message' => $message,
          'data' => $message
        ];
        return response($data);
      }
      if($license->expiry_date < date('Y-m-d')) {
        $message = "License Expired @$license->expiry_date";
        $data = [
          'status' => 200,
          'message' => $message,
          'data' => $message
        ];
        return response($data);
      }
      if($license->status == "off") {
        $message = "License Disabled. (Expiry:$license->expiry_date)";
        $data = [
          'status' => 200,
          'message' => $message,
          'data' => $message
        ];
        return response($data);
      }
      $message = $license->uid.'-MTcopier';
      $data = [
        'status' => 200,
        'message' => 'good_valid',
        'data' => $message
      ];
      return response($data);
    } catch (\Exception $th) {
      $message = "Something Wrong.";
      $message = $th->getMessage() . '  '  . $th->getLine();
      $data = [
        'status' => 200,
        'message' => $message,
        'data' => $message
      ];
      return response($data);
    }
  }
  
  public function licenses(Request $request)
  {
    try { 
      $uid = $request->uid;
      // $licenses = DB::table('licenses')->get();
      $count = 10;
      if(isset($request->count)) {
        if($request->count == "all") {
          $count = DB::table('licenses')->count();
        } else {
          if($request->count > 10) {
            $count = $request->count;
          }
        }
      }
      $licenses = DB::table('licenses')->paginate($count);
      $total = DB::table('licenses')->count();
      $expired = DB::table('licenses')->where('expiry_date', '<', date("Y-m-d"))->count();
      $disabled = DB::table('licenses')->where('status', "off")->count();
      $data = [
        'licenses' => $licenses,
        'count'=>$count,
        'total'=>$total,
        'expired'=>$expired,
        'disabled'=>$disabled
      ];

      return view('license.licenses', $data);
    } catch (\Exception $th) {
      $message = $th->getMessage() . '  ' . $th->getLine();
      Session::flash('swal_error', $message);
      return redirect()->route('main');
    }
  }
  
  public function edit_license(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'id' => 'required|exists:licenses,id',
      ]
    );

    if ($validator->fails()) {
      $message = $validator->errors()->first();
      Session::flash('swal_error', $message);
      return redirect()->route('licenses');
    }

    try { 
      $uid = $request->uid;
      $license = DB::table('licenses')->where('id', $request->id)->first();
      return view('license.edit_license', ['license' => $license]);
    } catch (\Exception $th) {
      $message = $th->getMessage() . '  ' . $th->getLine();
      Session::flash('swal_error', $message);
      return redirect()->route('licenses');
    }
  }
   
  public function new_license(Request $request)
  {
    $expiry = Carbon::now()->addMonths(1)->format('Y-m-d');
    // dd($expiry);
    return view('license.new_license', ['expiry'=>$expiry]);
  }

  public function store_license(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'uid' => 'required|unique:licenses,uid|min:5',
        'expiry' => 'required',
      ]
    );

    if ($validator->fails()) {
      $message = $validator->errors()->first();
      Session::flash('swal_error', $message);
      return redirect()->back()->withInput($request->all());
    }

    try {
      $uid = $request->uid;
      $expiry = Carbon::parse($request->expiry)->format('Y-m-d');
      $status = "off";
      if(isset($request->on)) {
        $status = 'on';
      }
      $insert = [
        'uid' => $uid,
        'expiry_date' => $expiry,
        'status' => $status
      ];
      $id = DB::table('licenses')->insertGetId($insert);
      $message = "Stored Successfully.";
      Session::flash('swal_success', $message);
      return redirect()->route('licenses');
    } catch (\Exception $th) {
      $message = $th->getMessage() . '  ' . $th->getLine();
      Session::flash('swal_error', $message);
      return redirect()->back()->withInput($request->all());
    }
  }
  
  public function update_license(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'id' => 'required|exists:licenses,id',
        'uid' => 'required|min:5',
        'expiry' => 'required',
      ]
    );

    if ($validator->fails()) {
      $message = $validator->errors()->first();
      Session::flash('swal_error', $message);
      return redirect()->back()->withInput($request->all());
    }

    try {
      $id = $request->id;
      $uid = $request->uid;
      //
      $alreadyExists = DB::table('licenses')->where('uid', $uid)->whereNot('id', $id)->first();
      if(isset($alreadyExists)) {
        $message = "$uid has been already taken.";
        Session::flash('swal_error', $message);
        return redirect()->back()->withInput($request->all());
      }
      //
      $expiry = Carbon::parse($request->expiry)->format('Y-m-d');
      $status = "off";
      if(isset($request->on)) {
        $status = 'on';
      }
      $update = [
        'uid' => $uid,
        'expiry_date' => $expiry,
        'status' => $status
      ];
      DB::table('licenses')->where('id', $id)->update($update);
      $message = "Updated Successfully.";
      Session::flash('swal_success', $message);
      return redirect()->route('licenses');
    } catch (\Exception $th) {
      $message = $th->getMessage() . '  ' . $th->getLine();
      Session::flash('swal_error', $message);
      return redirect()->back()->withInput($request->all());
    }
  }
  
  public function delete_license(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'id' => 'required|exists:licenses,id',
      ]
    );

    if ($validator->fails()) {
      $message = $validator->errors()->first();
      Session::flash('swal_error', $message);
      return redirect()->back()->withInput($request->all());
    }

    try {
      $id = $request->id;
      //
      DB::table('licenses')->where('id', $id)->delete();
      $message = "Deleted Successfully.";
      Session::flash('swal_success', $message);
      return redirect()->route('licenses');
    } catch (\Exception $th) {
      $message = $th->getMessage() . '  ' . $th->getLine();
      Session::flash('swal_error', $message);
      return redirect()->back()->withInput($request->all());
    }
  }
    
  public function settings(Request $request)
  {
    try { 
      $auth_code = DB::table('others')->where('name', "auth_code")->first()->value ?? "";
      $security_pin = env("SECRET_PIN");
      return view('settings', ['auth_code' => $auth_code, 'security_pin' => $security_pin]);
    } catch (\Exception $th) {
      $message = $th->getMessage() . '  ' . $th->getLine();
      Session::flash('swal_error', $message);
      return redirect()->route('licenses');
    }
  }

  public function store_settings(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'auth_code' => 'required|min:5',
        // 'security_pin' => 'required|min:5'
      ]
    );

    if ($validator->fails()) {
      $message = $validator->errors()->first();
      Session::flash('swal_error', $message);
      return redirect()->back()->withInput($request->all());
    }

    try {
      $auth_code = $request->auth_code;
      $security_pin = $request->security_pin;
      // Auth Code Update
      $update = [
        'value' => $auth_code
      ];
      DB::table('others')->where('name', "auth_code")->update($update);
      // Security Pin Update
      // $key = "SECRET_PIN";
      // file_put_contents(app()->environmentFilePath(), str_replace(
      //   $key . '=' . env($key),
      //   $key . '=' . $security_pin,
      //   file_get_contents(app()->environmentFilePath())
      // ));
      // 
      $message = "Settings Updated Successfully.";
      Session::flash('swal_success', $message);
      return redirect()->route('settings');
    } catch (\Exception $th) {
      $message = $th->getMessage() . '  ' . $th->getLine();
      Session::flash('swal_error', $message);
      return redirect()->back()->withInput($request->all());
    }
  }
}
