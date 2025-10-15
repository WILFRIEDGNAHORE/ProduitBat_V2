<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductsImage;
use App\Models\ProductsAttribute;
use Auth;
use App\Models\AdminsRole;

class ProductService
{
    public function products()
    {
        $products = Product::with('category')->get();
        // Set Admin/Subadmin Permissions for Products
        $productsModuleCount = AdminsRole::where([
            'subadmin_id' => Auth::guard('admin')->user()->id,
            'module' => 'products'
        ])->count();
        $status = "success";
        $message = "";
        $productsModule = [];
        if (Auth::guard('admin')->user()->role == "admin") {
            $productsModule = [
                'view_access' => 1,
                'edit_access' => 1,
                'full_access' => 1
            ];
        } elseif ($productsModuleCount == 0) {
            $status = "error";
            $message = "This feature is restricted for you!";
        } else {
            $productsModule = AdminsRole::where([
                'subadmin_id' => Auth::guard('admin')->user()->id,
                'module' => 'products'
            ])->first()->toArray();
        }
        return [
            "products" => $products,
            "productsModule" => $productsModule,
            "status" => $status,
            "message" => $message
        ];
    }

    public function updateProductStatus($data)
    {
        $status = ($data['status'] == "Active") ? 0 : 1;
        Product::where('id', $data['product_id'])->update(['status' => $status]);
        return $status;
    }
    public function deleteProduct($id)
    {
        Product::where('id', $id)->delete();
        $message = 'Product deleted successfully! ';
        return ['message' => $message];
    }

    public function addEditProduct($request)
    {
        $data = $request->all();

        if (isset($data['id']) && $data['id'] != "") {
            $product = Product::find($data['id']);
            $message = "Product updated successfully!";
        } else {
            $product = new Product;
            $message = "Product added successfully!";
        }

        $product->admin_id = Auth::guard('admin')->user()->id;
        $product->admin_role = Auth::guard('admin')->user()->role;
        $product->category_id = $data['category_id'];
        $product->product_name = $data['product_name'];
        $product->product_code = $data['product_code'];
        $product->product_color = $data['product_color'];
        $product->family_color = $data['family_color'];
        $product->group_code = $data['group_code'];
        $product->product_weight = $data['product_weight'] ?? 0;
        $product->product_price = $data['product_price'];
        $product->product_gst = $data['product_gst'] ?? 0;
        $product->product_discount = $data['product_discount'] ?? 0;
        $product->is_featured = $data['is_featured'] ?? 'No';

        // Calculate discount & final price
        if (!empty($data['product_discount']) && $data['product_discount'] > 0) {
            $product->discount_applied_on = 'product';
            $product->product_discount_amount = ($data['product_price'] * $data['product_discount']) / 100;
        } else {
            $getCategoryDiscount = Category::select('discount')->where('id', $data['category_id'])->first();

            if ($getCategoryDiscount && $getCategoryDiscount->discount > 0) {
                $product->discount_applied_on = 'category';
                $product->product_discount = $getCategoryDiscount->discount;
                $product->product_discount_amount = ($data['product_price'] * $getCategoryDiscount->discount) / 100;
            } else {
                $product->discount_applied_on = "";
                $product->product_discount_amount = 0;
            }
        }

        $product->final_price = $data['product_price'] - $product->product_discount_amount;

        // Optional fields
        $product->description = $data['description'] ?? "";
        $product->wash_care = $data['wash_care'] ?? "";
        $product->search_keywords = $data['search_keywords'] ?? "";
        $product->meta_title = $data['meta_title'] ?? "";
        $product->meta_keywords = $data['meta_keywords'] ?? "";
        $product->meta_description = $data['meta_description'] ?? "";
        $product->status = 1;

        // Upload main image
        if (!empty($data['main_image_hidden'])) {
            $sourcePath = public_path('temp/' . $data['main_image_hidden']);
            $destinationPath = public_path('front/images/products/' . $data['main_image_hidden']);

            if (file_exists($sourcePath)) {
                @copy($sourcePath, $destinationPath);
                @unlink($sourcePath);
            }

            $product->main_image = $data['main_image_hidden'];
        }

        // Upload product video
        if (!empty($data['product_video_hidden'])) {
            $sourcePath = public_path('temp/' . $data['product_video_hidden']);
            $destinationPath = public_path('front/videos/products/' . $data['product_video_hidden']);

            if (file_exists($sourcePath)) {
                @copy($sourcePath, $destinationPath);
                @unlink($sourcePath);
            }

            $product->product_video = $data['product_video_hidden'];
        }

        // Final fallback (if nothing uploaded in Dropzone)
        $product->main_image = $request->main_image ?? $product->main_image;
        $product->product_video = $request->product_video ?? $product->product_video;

        // Save product
        $product->save();

        // Upload Alternate Images
        if (!empty($data['product_images'])) {
            // Ensure we have an array
            $imageFiles = is_array($data['product_images'])
                ? $data['product_images']
                : explode(',', $data['product_images']);

            // Remove any empty values
            $imageFiles = array_filter($imageFiles);

            foreach ($imageFiles as $index => $filename) {
                $sourcePath = public_path('temp/' . $filename);
                $destinationPath = public_path('front/images/products/' . $filename);
                if (file_exists($sourcePath)) {
                    @copy($sourcePath, $destinationPath);
                    @unlink($sourcePath);
                }

                \App\Models\ProductsImage::create([
                    'product_id' => $product->id,
                    'image' => $filename,
                    'sort' => $index,
                    'status' => 1
                ]);
            }
        }

        // ===============================
        // ✅ Add Product Attributes
        // ===============================
        $total_stock = 0;

        // New attributes
        if (!empty($data['sku'])) {
            foreach ($data['sku'] as $key => $value) {
                if (!empty($value) && !empty($data['size'][$key]) && !empty($data['price'][$key])) {

                    // SKU already exists check
                    $attrCountSKU = ProductsAttribute::join('products', 'products.id', '=', 'products_attributes.product_id')
                        ->where('sku', $value)
                        ->count();

                    if ($attrCountSKU > 0) {
                        $message = "SKU already exists. Please add another SKU!";
                        return redirect()->back()->with('success_message', $message);
                    }

                    // Size already exists check
                    $attrCountSize = ProductsAttribute::join('products', 'products.id', '=', 'products_attributes.product_id')
                        ->where([
                            'product_id' => $product->id,
                            'size' => $data['size'][$key],
                        ])
                        ->count();

                    if ($attrCountSize > 0) {
                        $message = "Size already exists. Please add another Size!";
                        return redirect()->back()->with('success_message', $message);
                    }

                    // Ensure stock is numeric
                    $stockValue = !empty($data['stock'][$key]) ? $data['stock'][$key] : 0;

                    // Create new attribute
                    $attribute = new ProductsAttribute();
                    $attribute->product_id = $product->id;
                    $attribute->sku = $value;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $stockValue;
                    $attribute->sort = $data['sort'][$key] ?? 0;
                    $attribute->status = 1;
                    $attribute->save();

                    $total_stock += $stockValue;
                }
            }
        }

        // ===============================
        // ✅ Edit Product Attributes
        // ===============================
        if (isset($data['attrId'])) {
            foreach ($data['attrId'] as $key => $attrId) {
                if (!empty($attrId)) {
                    $update_attr = [
                        'price' => $data['update_price'][$key],
                        'stock' => $data['update_stock'][$key],
                        'sort'  => $data['update_sort'][$key],
                    ];

                    ProductsAttribute::where('id', $attrId)->update($update_attr);
                    $total_stock += $data['update_stock'][$key];
                }
            }
        }

        // ===============================
        // ✅ Update Total Stock in Products table
        // ===============================
        Product::where('id', $product->id)->update(['stock' => $total_stock]);



        return $message;
    }

    public function updateAttributeStatus($data)
    {
        $status = ($data['status'] == "Active") ? 0 : 1;
        ProductsAttribute::where('id', $data['attribute_id'])->update(['status' => $status]);
        return $status;
    }




    // ✅ Upload Image
    public function handleImageUpload($file)
    {
        $imageName = time() . '.' . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('front/images/products'), $imageName);
        return $imageName;
    }

    // ✅ Upload Video
    public function handleVideoUpload($file)
    {
        $videoName = time() . '.' . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('front/videos/products'), $videoName);
        return $videoName;
    }

    // ✅ Delete Main Image
    public function deleteProductMainImage($id)
    {
        $product = Product::select('main_image')->where('id', $id)->first();

        if (!$product || !$product->main_image) {
            return "No image found.";
        }

        $imagePath = public_path('front/images/products/' . $product->main_image);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        Product::where('id', $id)->update(['main_image' => null]);

        return "Product main image has been deleted successfully!";
    }

    public function deleteProductImage($id)
    {
        $productImage = ProductsImage::select('image')->where('id', $id)->first();

        if (!$productImage || !$productImage->image) {
            return "No image found.";
        }

        $imagePath = public_path('front/images/products/' . $productImage->image);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        ProductsImage::where('id', $id)->delete();

        return "Product image has been deleted successfully!";
    }

    // ✅ Delete Video
    public function deleteProductVideo($id)
    {
        $productVideo = Product::select('product_video')->where('id', $id)->first();

        if (!$productVideo || !$productVideo->product_video) {
            return "No video found.";
        }

        $videoPath = public_path('front/videos/products/' . $productVideo->product_video);

        if (file_exists($videoPath)) {
            unlink($videoPath);
        }

        Product::where('id', $id)->update(['product_video' => null]);

        return "Product video has been deleted successfully!";
    }

    public function deleteProductAttribute($id)
    {
        // Delete Attribute
        ProductsAttribute::where('id', $id)->delete();
        return "Product Attribute has been deleted successfully!";
    }

    public function updatelmageSorting(array $sortedImages): void
    {
        foreach ($sortedImages as $imageData) {
            if (isset($imageData['id']) && isset($imageData['sort'])) {
                ProductsImage::where('id', $imageData['id'])->update([
                    'sort' => $imageData['sort']
                ]);
            }
        }
    }
}
