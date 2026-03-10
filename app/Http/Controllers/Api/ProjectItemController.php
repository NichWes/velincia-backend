<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectItemController extends Controller
{
    // Fungsi get index (/projects/{project}/items)
    public function index(Project $project) {
        $this->authorizeProject($project);

        $items = $project->items()
            ->with('material')   // agar kalau material_id ada, langsung terbaca datanya
            ->latest()
            ->get();

        return response()->json($items);
    }

    // fungsi post store (/projects/{project}/items)
    public function store(Request $request, Project $project) {
        $this->authorizeProject($project);

        $data = $request->validate([
            'material_id' => ['nullable', 'exists:materials,id'],
            'custom_name' => ['nullable', 'string', 'max:255'],
            'qty_needed' => ['required', 'integer', 'min:1'],
            'qty_purchased' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in(['not_bought','partial','complete','substituted'])],
            'notes' => ['nullable', 'string'],
        ]);

        if (empty($data['material_id']) && empty($data['custom_name'])) {
            return response()->json([
                'message' => 'material_id atau custom_name wajib diisi'
            ], 422);
        }

        $data['project_id'] = $project->id;

        // default qty_purchased
        $data['qty_purchased'] = $data['qty_purchased'] ?? 0;

        // enforce qty_purchased <= qty_needed
        if ($data['qty_purchased'] > $data['qty_needed']) {
            return response()->json([
                'message' => 'qty_purchased tidak boleh lebih besar dari qty_needed'
            ], 422);
        }

        // auto status (kalau status tidak dikirim)
        $data['status'] = $data['status'] ?? $this->calculateStatus($data['qty_needed'], $data['qty_purchased']);
        
        $item = ProjectItem::create($data);

        $this->refreshProjectStatus($project);

        return response()->json([
            'message' => 'Project item created',
            'item' => $item->load('material'),
        ], 201);
    }

    // fungsi patch update (/project-items/{item})
    public function update(Request $request, ProjectItem $item) {
        $this->authorizeProject($item->project);

        $data = $request->validate([
            'material_id' => ['nullable', 'exists:materials,id'],
            'custom_name' => ['nullable', 'string', 'max:255'],
            'qty_needed' => ['nullable', 'integer', 'min:1'],
            'qty_purchased' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in(['not_bought','partial','complete','substituted'])],
            'notes' => ['nullable', 'string'],
        ]);

        $newQtyNeeded = $data['qty_needed'] ?? $item->qty_needed;
        $newQtyPurchased = $data['qty_purchased'] ?? $item->qty_purchased;

        if ($newQtyPurchased > $newQtyNeeded) {
            return response()->json([
                'message' => 'qty_purchased tidak boleh lebih besar dari qty_needed'
            ], 422);
        }

        // enforce: material_id OR custom_name harus tetap ada
        $newMaterialId = array_key_exists('material_id', $data) ? $data['material_id'] : $item->material_id;
        $newCustomName = array_key_exists('custom_name', $data) ? $data['custom_name'] : $item->custom_name;

        if (empty($newMaterialId) && empty($newCustomName)) {
            return response()->json([
                'message' => 'material_id atau custom_name wajib diisi'
            ], 422);
        }

        // auto status kalau user update qty tapi tidak kirim status
        if (!array_key_exists('status', $data) && (array_key_exists('qty_needed', $data) || array_key_exists('qty_purchased', $data))) {
            $data['status'] = $this->calculateStatus($newQtyNeeded, $newQtyPurchased);
        }

        $item->update($data);

        $this->refreshProjectStatus($item->project);

        return response()->json($item->fresh()->load('material'));
    }

    // fungsi delete destroy (/project-items/{item})
    public function destroy(ProjectItem $item)  {
        $this->authorizeProject($item->project);

        $project = $item->project;

        $item->delete();

        $this->refreshProjectStatus($project);

        return response()->json(['message' => 'Deleted']);
    }

     private function authorizeProject(Project $project): void{
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }

     private function calculateStatus(int $needed, int $purchased): string {
            if ($purchased <= 0) {
            return ProjectItem::STATUS_NOT_BOUGHT;
        }

        if ($purchased >= $needed) {
            return ProjectItem::STATUS_COMPLETE;
        }

        return ProjectItem::STATUS_PARTIAL;
    }

    private function refreshProjectStatus(Project $project): void {
        $items = $project->items()->get();

        if ($items->isEmpty()) {
            $project->update([
                'status' => Project::STATUS_DRAFT,
            ]);
            return;
        }

        $allCompleted = $items->every(function ($item) {
            return in_array($item->status, [
                ProjectItem::STATUS_COMPLETE,
                ProjectItem::STATUS_SUBSTITUTED,
            ]);
        });

        $project->update([
            'status' => $allCompleted
                ? Project::STATUS_COMPLETED
                : Project::STATUS_ACTIVE,
        ]);
    }
}
