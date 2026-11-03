<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserRequest;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        return view('users.index', ['users' => User::with('patient')->latest()->paginate(15)]);
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function edit(User $user): View
    {
        return view('users.edit', ['user' => $user]);
    }

    public function update(AdminUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $role = $data['account_type'] === 'nurse' ? 'admin' : 'user';

        DB::transaction(function () use ($data, $role, $user): void {
            $patient = $user->patient;

            if ($role === 'user') {
                $patient ??= Pasien::where('email', $user->email)->first();

                if ($patient) {
                    $patient->update([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'phone' => $data['phone'] ?? $patient->phone,
                    ]);
                } else {
                    $patient = Pasien::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'phone' => $data['phone'] ?? null,
                    ]);
                }
            }

            $userUpdate = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $role,
                'patient_id' => $role === 'user' ? $patient?->id : null,
            ];

            if (! empty($data['password'])) {
                $userUpdate['password'] = $data['password'];
            }

            $user->update($userUpdate);
        });

        return to_route('users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function store(AdminUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $role = $data['account_type'] === 'nurse' ? 'admin' : 'user';
        DB::transaction(function () use ($data, $role): void {
            $patient = null;

            if ($role === 'user') {
                $patient = Pasien::where('email', $data['email'])
                    ->orWhere('phone', $data['phone'])
                    ->first();

                if (! $patient) {
                    $patient = Pasien::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'phone' => $data['phone'],
                    ]);
                }
            }

            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $role,
                'patient_id' => $role === 'user' ? $patient->id : null,
            ]);
        });

        return to_route('users.index')->with('success', 'Akun '.($role === 'admin' ? 'perawat' : 'pasien').' berhasil dibuat.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->is(auth()->user()), 422, 'Akun yang sedang digunakan tidak dapat dihapus.');
        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus.');
    }
}
