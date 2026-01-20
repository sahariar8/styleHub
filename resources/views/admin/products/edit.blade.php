@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gradient bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 bg-clip-text text-transparent mb-6">Edit Product</h1>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-semibold">Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Category</label>
                <select name="category_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Brand</label>
                <select name="brand_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <option value="">Select Brand (Optional)</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400">
            </div>
        </div>

        <div>
            <label class="block mb-1 font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
            <div>
                <label class="block mb-1 font-semibold">Image</label>
                <input type="file" name="image" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="h-32 mt-2 object-cover rounded">
                @endif
            </div>
            <div class="flex items-center mt-6 md:mt-0">
                <input type="checkbox" name="is_published" value="1" class="mr-2" {{ old('is_published', $product->is_published) ? 'checked' : '' }}>
                <label class="font-semibold">Publish Product</label>
            </div>
        </div>

        <div class="mt-4">
            <h2 class="font-bold text-lg mb-2">Variants</h2>
            <div id="variants-container" class="space-y-2">
                @foreach($product->variants as $index => $variant)
                <div class="flex gap-2">
                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                    <input type="text" name="variants[{{ $index }}][sku]" value="{{ $variant->sku }}" placeholder="SKU" class="border rounded px-2 py-1 w-1/4">
                    <input type="text" name="variants[{{ $index }}][attribute]" value="{{ $variant->attribute }}" placeholder="Attribute" class="border rounded px-2 py-1 w-1/4">
                    <input type="number" step="0.01" name="variants[{{ $index }}][price]" value="{{ $variant->price }}" placeholder="Price" class="border rounded px-2 py-1 w-1/4">
                    <input type="number" name="variants[{{ $index }}][stock]" value="{{ $variant->stock }}" placeholder="Stock" class="border rounded px-2 py-1 w-1/4">
                    <button type="button" onclick="removeVariant(this)" class="bg-red-500 text-white px-2 rounded hover:bg-red-600">X</button>
                </div>
                @endforeach
            </div>
            <button type="button" onclick="addVariant()" class="mt-2 px-4 py-2 bg-gradient-to-r from-green-400 to-blue-500 text-white rounded hover:from-green-500 hover:to-blue-600 transition">Add Variant</button>
        </div>

        <div class="mt-6">
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-lg hover:from-purple-600 hover:to-pink-600 transition">Update Product</button>
        </div>
    </form>
</div>

<script>
let variantIndex = {{ $product->variants->count() }};
function addVariant() {
    const container = document.getElementById('variants-container');
    const div = document.createElement('div');
    div.classList.add('flex', 'gap-2');
    div.innerHTML = `
        <input type="text" name="variants[${variantIndex}][sku]" placeholder="SKU" class="border rounded px-2 py-1 w-1/4">
        <input type="text" name="variants[${variantIndex}][attribute]" placeholder="Attribute" class="border rounded px-2 py-1 w-1/4">
        <input type="number" step="0.01" name="variants[${variantIndex}][price]" placeholder="Price" class="border rounded px-2 py-1 w-1/4">
        <input type="number" name="variants[${variantIndex}][stock]" placeholder="Stock" class="border rounded px-2 py-1 w-1/4">
        <button type="button" onclick="removeVariant(this)" class="bg-red-500 text-white px-2 rounded hover:bg-red-600">X</button>
    `;
    container.appendChild(div);
    variantIndex++;
}

function removeVariant(button) {
    button.parentElement.remove();
}
</script>
@endsection
