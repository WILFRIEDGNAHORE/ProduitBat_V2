<?php

namespace App\Services\Admin;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Session;
class AdminService
{
    public function login($data)
    {
        if(Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']]))
        {
            //Remember me Admin Email and Password
            if(!empty($data["remember"]))
            {
                setcookie("email", $data["email"], time() + 3600);
                setcookie("password", $data["password"], time() + 3600);
            }else{
                setcookie("email", "");
                setcookie("password", "");
            }
            
            
           $loginStatus = 1;
        }
        else
        {
            $loginStatus = 0;
        }
        return $loginStatus;
    }
    public function verifyPassword($data){
        if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
            return "true";
        } else {
            return "false";
        }
    }
    public function updatePassword($data){
if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
   if($data['new_pwd'] == $data['confirm_pwd']){
    Admin::where('email', Auth::guard('admin')->user()->email)
        ->update(['password' => bcrypt($data['new_pwd'])]);
        $status = "success";
        $message = "Password updated successfully";
   }else{
    $status = "error";
    $message = "New Password and Confirm Password not matched";
   }
}else{
    $status = "error";
    $message = "Current password is not matched";
}
return ["status" => $status, "message" => $message];
    }

    public function updateDetails($data){
        Session::put('page', 'update-details');
        Admin::where('email', Auth::guard('admin')->user()->email)
        ->update(['name' => $data['name'], 'mobile' => $data['mobile']]);
    }
}
