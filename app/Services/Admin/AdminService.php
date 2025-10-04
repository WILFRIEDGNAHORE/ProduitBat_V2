<?php

namespace App\Services\Admin;

use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Intervention\Image\Laravel\Facades\Image; // ✅ Bon Facade pour Laravel 10 + v3
use Session;

class AdminService
{
    public function login($data)
    {
        if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            // Remember me Admin Email and Password
            if (!empty($data["remember"])) {
                setcookie("email", $data["email"], time() + 3600);
                setcookie("password", $data["password"], time() + 3600);
            } else {
                setcookie("email", "");
                setcookie("password", "");
            }

            $loginStatus = 1;
        } else {
            $loginStatus = 0;
        }
        return $loginStatus;
    }

    public function verifyPassword($data)
    {
        if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
            return "true";
        } else {
            return "false";
        }
    }

    public function updatePassword($data)
    {
        if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
            if ($data['new_pwd'] == $data['confirm_pwd']) {
                Admin::where('email', Auth::guard('admin')->user()->email)
                    ->update(['password' => bcrypt($data['new_pwd'])]);
                $status = "success";
                $message = "Password updated successfully";
            } else {
                $status = "error";
                $message = "New Password and Confirm Password not matched";
            }
        } else {
            $status = "error";
            $message = "Current password is not matched";
        }
        return ["status" => $status, "message" => $message];
    }

    public function updateDetails($request)
    {
        $data = $request->all();

        // Upload Admin Image
        if ($request->hasFile('image')) {
            $image_tmp = $request->file('image');
            if ($image_tmp->isValid()) {
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(111, 99999) . '.' . $extension;
                $image_path = public_path('admin/images/photos/' . $imageName);

                Image::read($image_tmp)->save($image_path); // ✅ Utilisation Facade Image
            }
        } else if (!empty($data['current_image'])) {
            $imageName = $data['current_image'];
        } else {
            $imageName = "";
        }

        // Update Admin Details
        Admin::where('email', Auth::guard('admin')->user()->email)->update([
            'name'   => $data['name'],
            'mobile' => $data['mobile'],
            'image'  => $imageName
        ]);
    }

    public function deleteProfileImage($adminId)
{
    $profileImage = Admin::where('id', $adminId)->value('image');
    if ($profileImage) {
        $profile_image_path = 'admin/images/photos/' . $profileImage;
        if (file_exists(public_path($profile_image_path))) {
            unlink(public_path($profile_image_path));
        }
        Admin::where('id', $adminId)->update(['image' => null]);
        return ['status' => true, 'message' => 'Profile image deleted successfully!'];
    }
    return ['status' => false, 'message' => 'Profile image not found!'];
}

}
