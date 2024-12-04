<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'id') === null ? 'id' : $request->input('sortBy', 'id');
        $sortOrder = $request->input('sortOrder', 'asc');
        $filter = $request->input('filter', '');

        $query = Role::query();
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
        return Inertia::render('Admin/Roles/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoleRequest $request): RedirectResponse
    {
        Role::create($request->validated());
        return to_route('roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $role = Role::findById($id);
        return Inertia::render('Admin/Roles/Edit', [
            'role' => new RoleResource($role),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateRoleRequest $request, string $id): RedirectResponse
    {
        $role = Role::findById($id);
        $role->update($request->validated());
        return to_route('roles.index');
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
