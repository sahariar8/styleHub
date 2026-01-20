<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::firstOrCreate(['name' => 'T-Shirts']);
        $brand = Brand::firstOrCreate(['name' => 'Nike']);

        $product = Product::create([
            'name' => 'Cool T-Shirt',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'price' => 29.99,
            'img' => 'products/cool-tshirt.jpg',
            'description' => 'High quality cotton t-shirt.',
            'is_published' => true,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TSHIRT-RED-M',
            'color' => 'Red',
            'size' => 'M',
            'price' => 29.99,
            'stock' => 10,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'TSHIRT-BLUE-L',
            'color' => 'Blue',
            'size' => 'L',
            'price' => 29.99,
            'stock' => 5,
        ]);

    }
}
