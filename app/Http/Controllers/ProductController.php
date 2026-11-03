<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('products.index', ['products' => Product::withCount('orderItems')->latest()->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(6));
        $data['is_active'] = $request->boolean('is_active');
        $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        $data['video_path'] = $request->file('video')->store('videos');
        unset($data['video']);
        Product::create($data);

        return to_route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('video')) {
            if ($product->video_path) {
                Storage::disk('local')->delete($product->video_path);
            }
            $data['video_path'] = $request->file('video')->store('videos');
        }
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && ! Str::startsWith($product->thumbnail, ['http://', 'https://'])) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        unset($data['video']);
        $product->update($data);

        return to_route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->orderItems()->exists()) {
            $product->orderItems()->delete();
        }

        if ($product->accesses()->exists()) {
            $product->accesses()->delete();
        }

        if ($product->video_path) {
            Storage::disk('local')->delete($product->video_path);
        }
        if ($product->thumbnail && ! Str::startsWith($product->thumbnail, ['http://', 'https://'])) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();

        return to_route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
