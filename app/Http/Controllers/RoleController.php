<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
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

        $query = Role::query()->with('permissions');
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $roles = RoleResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Admin/Roles/RoleIndex', [
            'roles' => $roles,
            'filter' => $filter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Roles/Create', [
            'permissions' => PermissionResource::collection(Permission::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoleRequest $request): RedirectResponse
    {
        $role = Role::create([
            'name' => $request->name,
        ]);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions.*.name'));
        }
        return to_route('roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $role = Role::findById($id);
        $role->load('permissions');

        return Inertia::render('Admin/Roles/Edit', [
            'role' => new RoleResource($role),
            'permissions' => PermissionResource::collection(Permission::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateRoleRequest $request, string $id): RedirectResponse
    {
        $role = Role::findById($id);
        $role->update([
            'name' => $request->name,
        ]);
        $role->syncPermissions($request->input('permissions.*.name'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $role = Role::findById($id);
        $role->delete();
        return back();
    }
}
