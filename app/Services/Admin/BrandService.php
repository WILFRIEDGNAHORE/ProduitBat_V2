<?php

namespace App\Services\Admin;

use App\Models\Brand;
use Auth;
use App\Models\AdminsRole;
use Carbon\Carbon;

class BrandService
{
    public function brands()
    {
        $brands = Brand::get();
        $admin = Auth::guard('admin')->user();
        $status = "success";
        $message = "";
        $brandsModule = [];

        // Admin has full access
        if ($admin->role == "admin") {
            $brandsModule = [
                'view_access' => 1,
                'edit_access' => 1,
                'full_access' => 1
            ];
        } else {
            $brandsModuleCount = AdminsRole::where([
                'subadmin_id' => $admin->id,
                'module' => 'brands'
            ])->count();

            if ($brandsModuleCount == 0) {
                $status = "error";
                $message = "This feature is restricted for you!";
            } else {
                $brandsModule = AdminsRole::where([
                    'subadmin_id' => $admin->id,
                    'module' => 'brands'
                ])->first()->toArray();
            }
        }

        return [
            "brands" => $brands,
            "brandsModule" => $brandsModule,
            "status" => $status,
            "message" => $message
        ];
    }

    public function updateBrandStatus(array $data)
    {
        // Toggle based on current label coming from front-end
        // If current is 'Active', set to 0; if 'Inactive', set to 1
        $newStatus = (isset($data['status']) && $data['status'] === 'Active') ? 0 : 1;

        if (isset($data['brand_id'])) {
            Brand::where('id', $data['brand_id'])->update(['status' => $newStatus]);
        }

        return $newStatus;
    }
}
