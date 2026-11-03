<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('store.cart', ['items' => auth()->user()->cartItems()->with('product')->get()]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);
        CartItem::updateOrCreate(['user_id' => $request->user()->id, 'product_id' => $product->id], ['quantity' => 1]);

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->user_id === auth()->id(), 403);
        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
