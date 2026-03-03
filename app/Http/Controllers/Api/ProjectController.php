<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
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
}