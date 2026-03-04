<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectItem;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Create Project
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'project_type' => 'nullable|string|max:100',
            'budget_target' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $project = Project::create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'project_type' => $data['project_type'] ?? null,
            'budget_target' => $data['budget_target'] ?? null,
            'notes' => $data['notes'] ?? null,
            // status default dari migration (draft)
        ]);

        return response()->json([
            'message' => 'Project created',
            'project' => $project,
        ], 201);
    }

    // List Project user
    public function index()
    {
        $projects = Project::where('user_id', auth()->id())
            ->withCount('items')   
            ->latest()
            ->get();

        return response()->json($projects);
    }

    // Detail project
    public function show(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(
            $project->load(['items.material'])
        );
    }

    public function estimate(Project $project) {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $items = $project->items()->with('material')->get();

        $pricedItems = [];
        $unpricedItems = [];

        $totalNeeded = 0.0;
        $totalPurchased = 0.0;

        foreach ($items as $item) {
            $qtyNeeded = (int) $item->qty_needed;
            $qtyPurchased = (int) $item->qty_purchased;

            $name = $item->material
                ? trim(($item->material->name ?? '') . ' ' . ($item->material->variant ?? ''))
                : ($item->custom_name ?? 'Custom Item');

            $unit = $item->material?->unit;
            $price = $item->material?->price_estimate; 

            if ($price !== null) {
                $price = (float) $price;

                $subtotalNeeded = $price * $qtyNeeded;
                $subtotalPurchased = $price * $qtyPurchased;

                $totalNeeded += $subtotalNeeded;
                $totalPurchased += $subtotalPurchased;

                $pricedItems[] = [
                    'project_item_id' => $item->id,
                    'material_id' => $item->material_id,
                    'name' => $name,
                    'unit' => $unit,
                    'qty_needed' => $qtyNeeded,
                    'qty_purchased' => $qtyPurchased,
                    'price_estimate' => $price,
                    'subtotal_needed' => $subtotalNeeded,
                    'subtotal_purchased' => $subtotalPurchased,
                    'status' => $item->status,
                ];
            } else {
                $unpricedItems[] = [
                    'project_item_id' => $item->id,
                    'material_id' => $item->material_id,
                    'name' => $name,
                    'unit' => $unit,
                    'qty_needed' => $qtyNeeded,
                    'qty_purchased' => $qtyPurchased,
                    'price_estimate' => null,
                    'status' => $item->status,
                    'reason' => $item->material_id ? 'Material belum punya price_estimate' : 'Item custom (tanpa material_id)',
                ];
            }
        }

        $progressPercent = $totalNeeded > 0
            ? round(($totalPurchased / $totalNeeded) * 100, 2)
            : 0;

        return response()->json([
            'project' => [
                'id' => $project->id,
                'title' => $project->title,
            ],
            'summary' => [
                'total_items' => $items->count(),
                'priced_items' => count($pricedItems),
                'unpriced_items' => count($unpricedItems),

                'total_estimate_needed' => $totalNeeded,
                'total_estimate_purchased' => $totalPurchased,
                'progress_percent' => $progressPercent,
            ],
            'priced' => $pricedItems,
            'unpriced' => $unpricedItems,
        ]);
    }
}