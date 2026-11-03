<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\View\View;

class AdminStoreController extends Controller
{
    public function index(): View
    {
        return view('store.admin', [
            'productCount' => Product::count(),
            'activeProductCount' => Product::where('is_active', true)->count(),
            'pendingOrderCount' => Order::where('status', 'pending')->count(),
            'paidOrderCount' => Order::where('status', 'paid')->count(),
            'recentOrders' => Order::with('user')->latest()->limit(5)->get(),
            'paymentMethods' => PaymentMethod::latest()->get(),
        ]);
    }

    public function paymentMethods(): View
    {
        return view('store.payment-methods', [
            'paymentMethods' => PaymentMethod::latest()->get(),
        ]);
    }
}
