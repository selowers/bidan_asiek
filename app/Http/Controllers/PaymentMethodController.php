<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:bank,qris'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'qr_code' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['type'] === 'bank') {
            $request->validate([
                'bank_name' => ['required'],
                'account_number' => ['required'],
            ]);
        }

        if ($request->hasFile('qr_code')) {
            $validated['qr_code_path'] = $request->file('qr_code')->store('payment-qr', 'public');
        }

        unset($validated['qr_code']);
        $validated['is_active'] = $request->boolean('is_active', true);

        PaymentMethod::create($validated);

        return to_route('admin.store')->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:bank,qris'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'qr_code' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['type'] === 'bank') {
            $request->validate([
                'bank_name' => ['required'],
                'account_number' => ['required'],
            ]);
        }

        if ($request->hasFile('qr_code')) {
            if ($paymentMethod->qr_code_path) {
                Storage::disk('public')->delete($paymentMethod->qr_code_path);
            }

            $validated['qr_code_path'] = $request->file('qr_code')->store('payment-qr', 'public');
        }

        unset($validated['qr_code']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $paymentMethod->update($validated);

        return to_route('admin.store')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        if ($paymentMethod->qr_code_path) {
            Storage::disk('public')->delete($paymentMethod->qr_code_path);
        }

        $paymentMethod->delete();

        return to_route('admin.store')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}
