<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Products
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1
                class="text-3xl font-extrabold bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 bg-clip-text text-transparent">
                Products
            </h1>

            <a href="{{ route('products.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white font-semibold
                       bg-gradient-to-r from-green-400 to-blue-500
                       hover:from-green-500 hover:to-blue-600
                       transition shadow">
                + Add Product
            </a>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
                <div
                    class="bg-white rounded-xl shadow hover:shadow-xl transition overflow-hidden border border-gray-100">

                    <img
                        src="{{ $product->image
                                ? asset('storage/'.$product->image)
                                : 'https://via.placeholder.com/400x200?text=No+Image' }}"
                        alt="{{ $product->name }}"
                        class="h-48 w-full object-cover">

                    <div class="p-5">
                        <h2 class="text-lg font-bold text-gray-800">
                            {{ $product->name }}
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $product->category->name ?? 'No Category' }}
                            • {{ $product->brand->name ?? 'No Brand' }}
                        </p>

                        <p class="mt-2 text-lg font-semibold text-gray-900">
                            ${{ number_format($product->price, 2) }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Stock: {{ $product->variants->sum('stock') }}
                        </p>

                        <p class="mt-2 text-sm text-gray-600">
                            {{ \Illuminate\Support\Str::limit($product->description, 60) }}
                        </p>

                        <div class="flex justify-between items-center mt-4">
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $product->is_published
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-red-100 text-red-700' }}">
                                {{ $product->is_published ? 'Published' : 'Draft' }}
                            </span>

                            <div class="flex gap-2">
                                <a href="{{ route('products.edit', $product) }}"
                                    class="px-3 py-1 text-sm rounded bg-blue-500 text-white hover:bg-blue-600 transition">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('products.destroy', $product) }}"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="px-3 py-1 text-sm rounded bg-red-500 text-white hover:bg-red-600 transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 col-span-full text-center">
                    No products found.
                </p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
