<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUhallRequest;
use App\Http\Resources\FactoryResource;
use App\Http\Resources\SiloResource;
use App\Http\Resources\UhallResource;
use App\Http\Resources\UhallSharedResource;
use App\Models\Factory;
use App\Models\Silo;
use App\Models\Uhall;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UhallController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Uhall::class);

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,factory_id,silo_id,name',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 10;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'name';
        $sortOrder = $validated['sortOrder'] ?? 'asc';
        $filter = $validated['filter'] ?? '';

        $query = Uhall::query()->with('factory', 'silo');
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $uhalls = UhallResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Nomenklature/Uhalls/UhallIndex', [
            'uhalls' => $uhalls,
            'filter' => $filter,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function show(Request $request): Response
    {
        Gate::authorize('viewAny', Uhall::class);

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,factory_id,silo_id,name',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 6;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'name';
        $sortOrder = $validated['sortOrder'] ?? 'asc';
        $filter = $validated['filter'] ?? '';

        $query = Uhall::query()->with(['factory', 'silo', 'uproductions']);
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $uhalls = UhallSharedResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Uproductions/Uhalls/Show', [
            'uhalls' => $uhalls,
            'filter' => $filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Uhall::class);

        return Inertia::render('Nomenklature/Uhalls/Create', [
            'factories' => FactoryResource::collection(Factory::all()),
            'silos' => SiloResource::collection(Silo::with(['factory'])->get()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUhallRequest $request): RedirectResponse
    {
        Gate::authorize('create', Uhall::class);

        Uhall::create([
            'name' => $request->name,
            'factory_id' => $request->factory['id'],
            'silo_id' => $request->silo['id'],
        ]);

        return to_route('uhalls.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Uhall $uhall): Response
    {
        Gate::authorize('update', $uhall);

        $uhall->load(['factory', 'silo']);

        return Inertia::render('Nomenklature/Uhalls/Edit', [
            'uhall' => new UhallResource($uhall),
            'factories' => FactoryResource::collection(Factory::all()),
            'silos' => SiloResource::collection(Silo::with(['factory'])->get()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateUhallRequest $request, Uhall $uhall): RedirectResponse
    {
        Gate::authorize('update', $uhall);

        $uhall->update([
            'name' => $request->name,
            'factory_id' => $request->factory['id'],
            'silo_id' => $request->silo['id'],
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uhall $uhall): RedirectResponse
    {
        Gate::authorize('delete', $uhall);

        if ($uhall->uproductions()->exists()) {
            return back()->withErrors([
                'delete' => 'Не може да се изтрие Халето, защото има свързани производствени процеси.'
            ]);
        }

        $uhall->delete();

        return back();
    }
}
