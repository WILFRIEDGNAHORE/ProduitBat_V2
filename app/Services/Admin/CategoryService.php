<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\AdminRole;
use Auth;

class CategoryService
{
    public function categories()
    {
        $categories = Category::with('parentcategory')->get();
        $admin = Auth::guard('admin')->user();

        $status = "success";
        $message = "";
        $categoriesModule = [];

        // Admin has full access
        if ($admin->role == "admin") {
            $categoriesModule = [
                'view_access' => 1,
                'edit_access' => 1,
                'full_access' => 1
            ];
        } else {
            $categoriesModuleCount = AdminRole::where([
                'subadmin_id' => $admin->id,
                'module' => 'categories'
            ])->count();

            if ($categoriesModuleCount == 0) {
                $status = "error";
                $message = "This feature is restricted for you!";
            } else {
                $categoriesModule = AdminRole::where([
                    'subadmin_id' => $admin->id,
                    'module' => 'categories'
                ])->first()->toArray();
            }
        }

        return [
            "categories" => $categories,
            "categoriesModule" => $categoriesModule,
            "status" => $status,
            "message" => $message
        ];
    }
}
