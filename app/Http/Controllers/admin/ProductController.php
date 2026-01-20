<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:admin|seller']);
    //     $this->middleware('role:admin')->only(['destroy']); // only destroy method
    //     $this->middleware('role:seller')->except(['destroy']); // everyone else
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'brand', 'variants'])->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(); // For dropdown
        $brands = Brand::all();       // For dropdown
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'required_with:variants|string|distinct',
            'variants.*.attribute' => 'required_with:variants|string',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.stock' => 'required_with:variants|integer|min:0',
        ]);

        // 1️⃣ Create product
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'description' => $request->description,
            'is_published' => $request->is_published ?? false,
        ]);

        // 2️⃣ Handle product image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
            $product->save();
        }

        // 3️⃣ Create variants if provided
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variant['sku'],
                    'attribute' => $variant['attribute'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $product)
    {
        $product->load(['category', 'brand', 'variants']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $product->load('variants');
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.sku' => 'required_with:variants|string|distinct',
            'variants.*.attribute' => 'required_with:variants|string',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.stock' => 'required_with:variants|integer|min:0',
        ]);

        // 1️⃣ Update product
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'description' => $request->description,
            'is_published' => $request->is_published ?? false,
        ]);

        // 2️⃣ Update product image
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
            $product->save();
        }

        // 3️⃣ Update or create variants
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (isset($variantData['id'])) {
                    $variant = ProductVariant::find($variantData['id']);
                    $variant->update($variantData);
                } else {
                    ProductVariant::create(array_merge($variantData, ['product_id' => $product->id]));
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete product image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete(); // variants cascade on delete
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
