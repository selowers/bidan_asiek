<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerbaruiPasienRequest;
use App\Http\Requests\SimpanPasienRequest;
use App\Models\Pasien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $categories = [
            'all' => 'Semua pasien',
            'child' => 'Pasien anak-anak',
            'male' => 'Pasien laki-laki',
            'female' => 'Pasien perempuan',
            'general' => 'Pasien umum',
            'kia' => 'Pasien KIA',
        ];
        $category = $request->query('category', 'all');
        $category = is_string($category) && array_key_exists($category, $categories) ? $category : 'all';
        $patientsQuery = Pasien::query();

        if (in_array($category, ['child', 'male', 'female'], true)) {
            $patientsQuery->where('patient_category', $category);
        } elseif (in_array($category, ['general', 'kia'], true)) {
            $patientsQuery->where('service_category', $category);
        }

        return view('pasien.index', [
            'patients' => $patientsQuery->latest()->paginate(15)->withQueryString(),
            'categories' => $categories,
            'category' => $category,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SimpanPasienRequest $request): RedirectResponse
    {
        Pasien::create($request->validated());

        return to_route('patients.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pasien $patient): View
    {
        return view('pasien.show', ['patient' => $patient->load('appointments')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasien $patient): View
    {
        return view('pasien.edit', ['patient' => $patient]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PerbaruiPasienRequest $request, Pasien $patient): RedirectResponse
    {
        $patient->update($request->validated());

        return to_route('patients.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pasien $patient): RedirectResponse
    {
        $patient->delete();

        return to_route('patients.index')->with('success', 'Data pasien berhasil dihapus.');
    }
}
