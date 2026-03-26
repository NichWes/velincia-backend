<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class AdminMaterialController extends Controller {
    public function index(Request $request) {
        $query = Material::query()->latest();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $materials = $query->paginate(10)->withQueryString();

        return view('admin.materials.index', compact('materials'));
    }

    public function create() {
        return view('admin.materials.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'variant' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'price_estimate' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        Material::create($validated);

        return redirect()->route('admin.materials.index')->with('success', 'Material berhasil ditambahkan.');
    }

    public function edit(Material $material) {
        return view('admin.materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material) {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'variant' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'price_estimate' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $material->update($validated);

        return redirect()->route('admin.materials.index')->with('success', 'Material berhasil diupdate.');
    }

    public function destroy(Material $material) {
        $material->delete();

        return redirect()->route('admin.materials.index')->with('success', 'Material berhasil dihapus.');
    }
}