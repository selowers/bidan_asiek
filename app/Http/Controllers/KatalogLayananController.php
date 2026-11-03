<?php

namespace App\Http\Controllers;

use App\Models\LayananKlinik;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KatalogLayananController extends Controller
{
    public function index(Request $request): View
    {
        $categories = [
            'all' => 'Semua layanan',
            'general' => 'Umum',
            'kia' => 'KIA',
        ];
        $category = $request->query('category', 'all');
        $category = is_string($category) && array_key_exists($category, $categories) ? $category : 'all';
        $services = LayananKlinik::query()->where('is_active', true);

        if ($category !== 'all') {
            $services->where('category', $category);
        }

        return view('catalog.index', [
            'services' => $services->orderBy('name')->paginate(12)->withQueryString(),
            'categories' => $categories,
            'category' => $category,
        ]);
    }
}
