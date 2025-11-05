<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;


class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menTShirtsCategory = Category::where('name', 'Men T-Shirt')->first();

        if (!$menTShirtsCategory) {
            return; // Arrête si la catégorie n'existe pas
        }

        $products = [
            
            [
                'product_name' => 'Blue T-Shirt',
                'product_code' => 'BT001',
                'product_color' => 'Dark Blue',
                'family_color' => 'Blue',
                'product_price' => 1000,
                'product_discount' => 10,
                'final_price' => 900,
            ],

            [
                'product_name' => 'Red T-Shirt',
                'product_code' => 'RT002',
                'product_color' => 'Bright Red',
                'family_color' => 'Red',
                'product_price' => 1200,
                'product_discount' => 15,
                'final_price' => 1020,
            ],
            [
                'product_name' => 'Green T-Shirt',
                'product_code' => 'GT003',
                'product_color' => 'Forest Green',
                'family_color' => 'Green',
                'product_price' => 1100,
                'product_discount' => 5,
                'final_price' => 1045,
            ],
            [
                'product_name' => 'White T-Shirt',
                'product_code' => 'WT004',
                'product_color' => 'Pure White',
                'family_color' => 'White',
                'product_price' => 900,
                'product_discount' => 0,
                'final_price' => 900,
            ],
            [
                'product_name' => 'Black T-Shirt',
                'product_code' => 'BLT005',
                'product_color' => 'Jet Black',
                'family_color' => 'Black',
                'product_price' => 1300,
                'product_discount' => 20,
                'final_price' => 1040,
            ],
        ];

        foreach ($products as $data) {
            Product::create([
                'category_id' => $menTShirtsCategory->id,
                'brand_id' => 1,
                'admin_id' => 1,
                'admin_role' => 'admin',
                'product_name' => $data['product_name'],
                'product_code' => $data['product_code'],
                'product_color' => $data['product_color'],
                'family_color' => $data['family_color'],
                'group_code' => substr($data['product_code'], 0, 2) . '000',
                'product_price' => $data['product_price'],
                'product_discount' => $data['product_discount'],
                'product_discount_amount' => ($data['product_price'] * $data['product_discount']) / 100,
                'discount_applied_on' => 'product',
                'product_gst' => 12,
                'final_price' => $data['final_price'],
                'main_image' => null,
                'product_weight' => 500,
                'product_video' => null,
                'description' => 'This is ' . $data['product_name'],
                'wash_care' => null,
                'search_keywords' => null,
                'fabric' => null,
                'pattern' => null,
                'sleeve' => null,
                'fit' => null,
                'occasion' => null,
                'stock' => 10,
                'sort' => 1,
                'meta_title' => null,
                'meta_description' => null,
                'meta_keywords' => null,
                'is_featured' => 'No',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
