<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ProductAccess;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        /** @var User|null $user */
        $user = Auth::user();
        abort_unless($user instanceof User, 403);

        $orders = $user->isAdmin() ? Order::with('user', 'items')->latest()->paginate(15) : $user->orders()->with('items')->latest()->paginate(15);

        return view('store.orders', compact('orders'));
    }

    public function checkout(): View
    {
        /** @var User|null $user */
        $user = Auth::user();
        abort_unless($user instanceof User, 403);

        $items = $user->cartItems()->with('product')->get()->filter(fn ($item) => $item->product->is_active);
        abort_if($items->isEmpty(), 422, 'Keranjang masih kosong.');

        $paymentMethods = PaymentMethod::where('is_active', true)->latest()->get();

        return view('store.checkout', [
            'items' => $items,
            'total' => $items->sum(fn ($item) => $item->product->price * $item->quantity),
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        abort_unless($user instanceof User, 403);

        $items = $user->cartItems()->with('product')->get()->filter(fn ($item) => $item->product->is_active);
        abort_if($items->isEmpty(), 422, 'Keranjang masih kosong.');

        $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $paymentMethod = PaymentMethod::whereKey($request->payment_method_id)->where('is_active', true)->firstOrFail();
        $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

        $order = DB::transaction(function () use ($items, $user, $request, $paymentMethod, $paymentProofPath): Order {
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                'total' => $items->sum(fn ($item) => $item->product->price * $item->quantity),
                'status' => 'pending',
                'payment_method_id' => $paymentMethod->id,
                'payment_proof_path' => $paymentProofPath,
            ]);

            foreach ($items as $item) {
                $order->items()->create(['product_id' => $item->product_id, 'product_name' => $item->product->title, 'price' => $item->product->price, 'quantity' => $item->quantity]);
            }
            $user->cartItems()->delete();

            return $order;
        });

        return to_route('orders.index')->with('success', 'Pesanan dibuat. Silakan tunggu konfirmasi pembayaran via '.($paymentMethod->type === 'bank' ? ($paymentMethod->bank_name ?? 'bank transfer') : 'QRIS').'.');
    }

    public function markPaid(Order $order): RedirectResponse
    {
        abort_unless($order->status === 'pending', 422);

        DB::transaction(function () use ($order): void {
            $order->update(['status' => 'paid', 'paid_at' => now()]);
            foreach ($order->items as $item) {
                ProductAccess::updateOrCreate(['user_id' => $order->user_id, 'product_id' => $item->product_id], ['order_id' => $order->id, 'started_at' => now(), 'expired_at' => now()->addDays($item->product->access_duration), 'status' => 'active']);
            }
        });

        return back()->with('success', 'Pembayaran dikonfirmasi dan akses video diberikan.');
    }

    public function markRejected(Order $order): RedirectResponse
    {
        abort_unless($order->status === 'pending', 422);

        $order->update([
            'status' => 'rejected',
            'paid_at' => null,
        ]);

        return back()->with('success', 'Pembayaran ditolak dan pesanan dibatalkan.');
    }
}
