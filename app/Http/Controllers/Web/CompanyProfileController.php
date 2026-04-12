<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Material;

class CompanyProfileController extends Controller
{
    public function home() {
        $featuredMaterials = Material::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        $topCategories = Material::where('is_active', true)
            ->select('category')
            ->distinct()
            ->take(4)
            ->pluck('category');

        return view('company.home', compact('featuredMaterials', 'topCategories'));
    }

    public function products() {
        $materials = Material::where('is_active', true)
            ->latest()
            ->paginate(9);

        $categories = Material::where('is_active', true)
            ->select('category')
            ->distinct()
            ->pluck('category');

        $totalProducts = Material::where('is_active', true)->count();

        return view('company.products', compact('materials', 'categories', 'totalProducts'));
    }

    public function about() {
        return view('company.about');
    }

    public function contact() {
        return view('company.contact');
    }
}