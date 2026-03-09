<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index() {
        $materials = Material::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return response()->json($materials);
    }

    public function show(Material $material) {
        return response()->json($material);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'category' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'variant' => 'nullable|string|max:100',
            'unit' => 'required|string|max:20',
            'price_estimate' => 'nullable|numeric|min:0',
        ]);

        $material = Material::create($data);

        return response()->json([
            'message' => 'Material created',
            'material' => $material,
        ], 201);
    }

    public function update(Request $request, Material $material) {
        $data = $request->validate([
            'category' => 'sometimes|string|max:100',
            'name' => 'sometimes|string|max:255',
            'brand' => 'nullable|string|max:100',
            'variant' => 'nullable|string|max:100',
            'unit' => 'sometimes|string|max:20',
            'price_estimate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $material->update($data);

        return response()->json([
            'message' => 'Material updated',
            'material' => $material->fresh(),
        ]);
    }

    public function destroy(Material $material) {
        $material->update([
            'is_active' => false,
        ]);

        return response()->json([
            'message' => 'Material deactivated',
        ]);
    }
}