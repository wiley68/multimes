<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class PermissinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,name',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 10;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'asc';
        $filter = $validated['filter'] ?? '';

        $query = Permission::query();
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $permissions = PermissionResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Admin/Permissions/PermissionIndex', [
            'permissions' => $permissions,
            'filter' => $filter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Permissions/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePermissionRequest $request): RedirectResponse
    {
        Permission::create($request->validated());
        return to_route('permissions.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $permission = Permission::findById($id);
        return Inertia::render('Admin/Permissions/Edit', [
            'permission' => new PermissionResource($permission),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePermissionRequest $request, string $id): RedirectResponse
    {
        $permission = Permission::findById($id);
        $permission->update($request->validated());
        return to_route('permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $permission = Permission::findById($id);
        $permission->delete();
        return back();
    }
}
