<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Create Project
    public function store (Request $request) {
        $request -> validate([
            'title' => 'required|string|max:255'
        ]);

        $project = Project::create([
            'user_id' => $request -> user() -> id,
            'title' => $request -> title,
            'project_type' => $request -> project_type, 
            'budget_target' => $request -> budget_target,
            'notes' => $request -> notes
        ]);

        return response() -> json([
            'message' => 'Project created',
            'project' => $project
        ], 201);    
    }

    // List Project user
    public function index() { 
        $projects = Project::where('user_id', auth()->id())->get();

        return response()->json($projects);
    }

    // Detail project
    public function show($id) {
        $project = Project::where('user_id', auth()->id())
            -> where('id', $id)
            -> firstOrFail();

        return response() -> json($project);
    }
}
