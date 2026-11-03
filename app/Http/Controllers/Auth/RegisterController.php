<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $patient = Pasien::where('email', $request->string('email')->toString())->firstOrFail();
        $user = User::create([
            ...$request->safe()->only(['name', 'email', 'password']),
            'role' => 'user',
            'patient_id' => $patient->id,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return to_route('home')->with('success', 'Akun berhasil dibuat.');
    }
}
