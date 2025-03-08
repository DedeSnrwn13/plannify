<?php

namespace App\Http\Controllers;

use App\Enums\WorkspaceVisibility;
use App\Http\Requests\WorkspaceRequest;
use App\Http\Resources\WorkspaceResource;
use App\Models\Workspace;
use App\Traits\HasFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class WorkspaceController extends Controller
{
    use HasFile;

    public function create(): Response
    {
        return inertia('Workspaces/Create', [
            'page_settings' => [
                'title' => 'Create Workspace',
                'subtitle' => 'Fill out this form to add a new workspace',
                'method' => 'POST',
                'action' => route('workspaces.store')
            ],
            'visibilities' => WorkspaceVisibility::options()
        ]);
    }

    public function store(WorkspaceRequest $request): RedirectResponse
    {
        $workspace = $request->user()->workspaces()->create([
            'name' => $name = $request->name,
            'slug' => str()->slug($name, str()->uuid(10)),
            'cover' => $this->upload_file($request, 'cover', 'workspaces/cover'),
            'logo' => $this->upload_file($request, 'logo', 'workspaces/logo'),
            'visibility' => $request->visibility
        ]);

        flashMessage('Workspace information saved successfully');

        return to_route('workspaces.show', $workspace);
    }

    public function show(Workspace $workspace): Response
    {
        return inertia(component: 'Workspaces/Show', props: [
            'workspace' => fn() => new WorkspaceResource($workspace)
        ]);
    }

    public function edit(Workspace $workspace): Response
    {
        return inertia(component: 'Workspaces/Setting', props: [
            'workspace' => fn() => new WorkspaceResource($workspace),
            'page_settings' => [
                'title' => 'Ediit Workspace',
                'subtitle' => 'Fill out this form to edit workspace',
                'method' => 'PUT',
                'action' => route('workspaces.update', $workspace),
            ],
            'visibilities' => WorkspaceVisibility::options(),
        ]);
    }

    public function update(Workspace $workspace, WorkspaceRequest $request): RedirectResponse
    {
        $workspace->update([
            'name' => $name = $request->name,
            'slug' => str()->slug($name . str()->uuid(10)),
            'cover' => $request->hasFile('cover') ? $this->upload_file($request, 'cover', 'workspaces/cover') : $workspace->cover,
            'logo' => $request->hasFile('logo') ? $this->upload_file($request, 'logo', 'workspaces/logo') : $workspace->logo,
            'visibility' => $request->visibility,
        ]);

        flashMessage('Successfully updated workspace');

        return to_route('workspaces.show', $workspace);
    }
}
