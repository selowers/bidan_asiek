<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(): View
    {
        return view('store.index', ['products' => Product::where('is_active', true)->latest()->paginate(12), 'accesses' => auth()->user()->productAccesses()->where('status', 'active')->where('expired_at', '>', now())->pluck('product_id')]);
    }
}
