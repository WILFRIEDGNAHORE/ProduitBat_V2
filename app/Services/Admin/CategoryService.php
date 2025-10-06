<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\AdminRole;
use Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

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

    public function addEditCategory($request)
    {
        $data = $request->all();

        // Déterminer si on ajoute ou modifie
        if (isset($data['id']) && $data['id'] != "") {
            $category = Category::find($data['id']);
            $message = "Category updated successfully!";
        } else {
            $category = new Category;
            $message = "Category added successfully!";
        }

        $category->parent_id = ! empty($data['parent_id']) ? $data['parent_id'] : null;

        // 📸 Upload Category Image
        if ($request->hasFile('category_image')) {
            $image_tmp = $request->file('category_image');
            if ($image_tmp->isValid()) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image_tmp);

                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(111, 99999) . '.' . $extension;
                // Ensure directory exists
                $dir = public_path('front/categories');
                if (!File::exists($dir)) {
                    File::makeDirectory($dir, 0755, true);
                }
                $image_path = $dir . DIRECTORY_SEPARATOR . $imageName;

                $image->save($image_path);
                $category->image = $imageName;
            }
        }

        // 📊 Upload Size Chart
        if ($request->hasFile('size_chart')) {
            $sizechart_tmp = $request->file('size_chart');
            if ($sizechart_tmp->isValid()) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($sizechart_tmp);

                $sizechart_extension = $sizechart_tmp->getClientOriginalExtension();
                $sizechart_image_name = rand(111, 99999) . '.' . $sizechart_extension;
                // Ensure directory exists
                $dirSize = public_path('front/sizecharts');
                if (!File::exists($dirSize)) {
                    File::makeDirectory($dirSize, 0755, true);
                }
                $sizechart_image_path = $dirSize . DIRECTORY_SEPARATOR . $sizechart_image_name;

                $image->save($sizechart_image_path);
                $category->size_chart = $sizechart_image_name;
            }
        }

        // 📝 Formatage du nom et de l’URL
        $data['category_name'] = str_replace("-", " ", ucwords(strtolower($data['category_name'])));
        $data['url'] = str_replace(" ", "-", strtolower($data['url']));

        // 🧩 Assignation des données
        $category->name = $data['category_name'];
        $category->url = $data['url'];
        $category->description = $data['description'] ?? '';
        $category->meta_title = $data['meta_title'] ?? '';
        $category->meta_description = $data['meta_description'] ?? '';
        $category->meta_keywords = $data['meta_keywords'] ?? '';

        // 🎯 Discount par défaut
        $category->discount = !empty($data['category_discount']) ? $data['category_discount'] : 0;

        // 🗂️ Menu Status
        $category->menu_status = !empty($data['menu_status']) ? 1 : 0;

        // ✅ Status par défaut
        $category->status = 1;

        // 💾 Sauvegarde
        $category->save();

        return $message;
    }

    /**
     * Toggle category status via AJAX
     * @param array $data expects ['status' => 'Active'|'Inactive', 'category_id' => int]
     * @return int new status 0|1
     */
    public function updateCategoryStatus($data)
    {
        // Determine new status based on current label coming from the UI
        $newStatus = ($data['status'] === 'Active') ? 0 : 1;

        // Fetch category and update safely
        $category = Category::find($data['category_id']);
        if ($category) {
            $category->status = $newStatus;
            $category->save();
            return (int) $category->status;
        }

        // If not found, return the computed intended status (UI will refresh on next load)
        return (int) $newStatus;
    }

    public function deleteCategory($id){
        Category :: where('id', $id)->delete();
        $message = 'Category deleted successfully!';
        return ['message' => $message];
    }
}
