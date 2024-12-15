<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiloResource;
use App\Models\Silo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class SiloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Silo::class);

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

        $query = Silo::query()->with(['mhalls', 'uhalls']);
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $silos = SiloResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Nomenklature/Silos/SiloIndex', [
            'silos' => $silos,
            'filter' => $filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Silo::class);

        return Inertia::render('Nomenklature/Silos/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Silo $silo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Silo $silo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Silo $silo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Silo $silo)
    {
        //
    }
}
