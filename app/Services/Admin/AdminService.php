<?php

namespace App\Services\Admin;
use Auth;
use Illuminate\Support\Facades\Hash;
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
}
