<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerbaruiJanjiTemuRequest;
use App\Http\Requests\PerbaruiStatusJanjiTemuRequest;
use App\Http\Requests\SimpanJanjiTemuRequest;
use App\Models\JanjiTemu;
use App\Models\LayananKlinik;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JanjiTemuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);
        $query = JanjiTemu::with(['patient', 'service']);
        $statuses = [
            'scheduled' => 'Menunggu konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'rejected' => 'Ditolak',
        ];

        if ($user->isAdmin()) {
            $filters = ['all' => 'Semua', ...$statuses];
            $filter = request()->query('status', 'scheduled');
            $filter = is_string($filter) && array_key_exists($filter, $filters) ? $filter : 'scheduled';

            if ($filter !== 'all') {
                $query->where('status', $filter);
            }

            $counts = JanjiTemu::query()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');
            $query->orderBy('appointment_at');
        } else {
            abort_unless($user->patient_id, 403, 'Anda belum terhubung dengan data pasien.');
            $query->where('patient_id', $user->patient_id);
            $filters = ['upcoming' => 'Akan datang', 'history' => 'Riwayat'];
            $filter = request()->query('view', 'upcoming');
            $filter = is_string($filter) && array_key_exists($filter, $filters) ? $filter : 'upcoming';

            if ($filter === 'upcoming') {
                $query->where('appointment_at', '>=', now())
                    ->whereNotIn('status', ['completed', 'cancelled', 'rejected'])
                    ->orderBy('appointment_at');
            } else {
                $query->where(function ($query): void {
                    $query->where('appointment_at', '<', now())
                        ->orWhereIn('status', ['completed', 'cancelled', 'rejected']);
                })->orderByDesc('appointment_at');
            }
        }

        return view('janji_temu.index', [
            'appointments' => $query->paginate(15)->withQueryString(),
            'filters' => $filters,
            'filter' => $filter,
            'statuses' => $statuses,
            'counts' => $counts ?? [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);
        $services = LayananKlinik::where('is_active', true)->orderBy('name')->get();

        if ($user->isAdmin()) {
            return view('janji_temu.create', [
                'patients' => Pasien::orderBy('name')->get(['id', 'name']),
                'doctors' => collect(),
                'services' => $services,
            ]);
        }

        $patient = $user->patient()->first();
        abort_unless($patient, 403, 'Data pasien Anda belum tersedia.');

        return view('janji_temu.create', [
            'patients' => collect([$patient->only(['id', 'name'])]),
            'doctors' => User::where('role', 'admin')->orderBy('name')->get(['id', 'name']),
            'patient' => $patient,
            'services' => $services,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SimpanJanjiTemuRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        if (! $user->isAdmin()) {
            $validated['patient_id'] = $user->patient_id;
        }

        $service = LayananKlinik::whereKey($validated['service_id'])->where('is_active', true)->firstOrFail();
        $validated['type'] = $validated['type'] ?? $service->name;
        $validated['status'] = 'scheduled';
        JanjiTemu::create($validated);

        return to_route('appointments.index')->with('success', $user->isAdmin()
            ? 'Janji temu berhasil dibuat dan menunggu konfirmasi.'
            : 'Pengajuan janji temu berhasil dikirim. Menunggu konfirmasi admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, JanjiTemu $appointment): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        if (! $user->isAdmin()) {
            abort_unless((int) $appointment->patient_id === (int) $user->patient_id, 403);
        }

        return view('janji_temu.show', [
            'appointment' => $appointment->load(['patient', 'service']),
            'statuses' => [
                'scheduled' => 'Menunggu konfirmasi',
                'confirmed' => 'Dikonfirmasi',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                'rejected' => 'Ditolak',
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, JanjiTemu $appointment): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        if (! $user->isAdmin()) {
            abort_unless((int) $appointment->patient_id === (int) $user->patient_id, 403);
            abort_unless($appointment->canPatientReschedule(), 403, 'Janji ini tidak dapat dijadwalkan ulang.');
        }

        $services = LayananKlinik::query()
            ->where('is_active', true)
            ->when($appointment->service_id, fn ($query) => $query->orWhere('id', $appointment->service_id))
            ->orderBy('name')
            ->get();

        return view('janji_temu.edit', [
            'appointment' => $appointment,
            'patients' => Pasien::orderBy('name')->get(['id', 'name']),
            'doctors' => User::where('role', 'admin')->orderBy('name')->get(['id', 'name']),
            'services' => $services,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PerbaruiJanjiTemuRequest $request, JanjiTemu $appointment): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        if (! $user->isAdmin()) {
            abort_unless((int) $appointment->patient_id === (int) $user->patient_id, 403);
            abort_unless($appointment->canPatientReschedule(), 403, 'Janji ini tidak dapat dijadwalkan ulang.');
            $appointment->update($request->validated() + ['status' => 'scheduled']);
        } else {
            $appointment->update($request->validated());
        }

        return to_route('appointments.index')->with('success', $user->isAdmin()
            ? 'Detail janji temu berhasil diperbarui.'
            : 'Permintaan perubahan jadwal berhasil dikirim ulang untuk konfirmasi.');
    }

    public function updateStatus(PerbaruiStatusJanjiTemuRequest $request, JanjiTemu $appointment): RedirectResponse
    {
        $nextStatus = $request->validated()['status'];
        $allowedTransitions = [
            'scheduled' => ['confirmed', 'cancelled', 'rejected'],
            'confirmed' => ['completed', 'cancelled'],
        ];

        abort_unless(in_array($nextStatus, $allowedTransitions[$appointment->status] ?? [], true), 422, 'Perubahan status janji temu tidak diizinkan.');

        $appointment->update(['status' => $nextStatus]);

        $statusLabels = ['confirmed' => 'dikonfirmasi', 'completed' => 'selesai', 'cancelled' => 'dibatalkan', 'rejected' => 'ditolak'];

        return back()->with('success', 'Janji temu berhasil '.$statusLabels[$nextStatus].'.');
    }

    public function cancel(Request $request, JanjiTemu $appointment): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        abort_unless(! $user->isAdmin() && (int) $appointment->patient_id === (int) $user->patient_id, 403);
        abort_unless($appointment->canPatientCancel(), 403, 'Janji hanya dapat dibatalkan lebih dari 24 jam sebelum jadwal.');

        $appointment->update(['status' => 'cancelled']);

        return to_route('appointments.index')->with('success', 'Janji temu berhasil dibatalkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, JanjiTemu $appointment): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User && $user->isAdmin(), 403);

        $appointment->delete();

        return to_route('appointments.index')->with('success', 'Janji temu berhasil dihapus.');
    }
}
