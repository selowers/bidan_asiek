<?php

namespace App\Http\Controllers;

use App\Http\Requests\LayananKlinikRequest;
use App\Models\LayananKlinik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LayananKlinikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('clinic_services.index', [
            'services' => LayananKlinik::withCount('appointments')->latest()->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('clinic_services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LayananKlinikRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['image_path'] = $request->file('image')->store('clinic-services', 'public');
        $data['is_active'] = $request->boolean('is_active', true);
        unset($data['image']);

        LayananKlinik::create($data);

        return to_route('admin.clinic-services.index')->with('success', 'Layanan katalog berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LayananKlinik $layananKlinik): View
    {
        return view('clinic_services.edit', ['service' => $layananKlinik]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LayananKlinikRequest $request, LayananKlinik $layananKlinik): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('clinic-services', 'public');
            Storage::disk('public')->delete($layananKlinik->image_path);
            $data['image_path'] = $newImagePath;
        }

        unset($data['image']);
        $layananKlinik->update($data);

        return to_route('admin.clinic-services.index')->with('success', 'Layanan katalog berhasil diperbarui.');
    }

    public function toggle(Request $request, LayananKlinik $layananKlinik): RedirectResponse
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $layananKlinik->update(['is_active' => ! $layananKlinik->is_active]);

        return to_route('admin.clinic-services.index')->with('success', $layananKlinik->is_active
            ? 'Layanan katalog diaktifkan.'
            : 'Layanan katalog dinonaktifkan.');
    }
}
