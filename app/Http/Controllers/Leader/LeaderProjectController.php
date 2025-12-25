<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Auth::user()->projects()->withCount('tasks')->latest()->get();

        return view('leader.projects.index', compact('projects'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teams = auth()->user()->teamsAsLeader;
        return view('leader.projects.create', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        Auth::user()->projects()->create($validated);

        return redirect()->route('leader.projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    //public function show(string $id)
    public function show(Project $project)
    {
        $this->authorizeProject($project);
        if ($project->leader_id !== Auth::id()) {
            abort(403);
        }
        $project->load('tasks.assignedTo');
        return view('leader.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    //public function edit(string $id)
    public function edit(Project $project)
    {
        $this->authorizeProject($project);
        if ($project->leader_id !== Auth::id()) {
            abort(403);
        }
        $teams = auth()->user()->teamsAsLeader;
        return view('leader.projects.edit', compact('project', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    //public function update(Request $request, string $id)
    public function update(Request $request, Project $project)
    {
        $this->authorizeProject($project);
        if ($project->leader_id !== Auth::id()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $project->update($validated);

        return redirect()->route('leader.projects.index')->with('success', 'Project updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    //public function destroy(string $id)
    public function destroy(Project $project)
    {
        $this->authorizeProject($project);
        if ($project->leader_id !== Auth::id()) {
            abort(403);
        }
        $project->delete();
        return back()->with('success', 'Projet deleted');
    }

    // Helper to authorize project actions
    private function authorizeProject(Project $project)
    {
        if ($project->leader_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }



}
