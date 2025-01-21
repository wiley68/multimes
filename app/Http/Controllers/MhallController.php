<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMhallRequest;
use App\Http\Resources\FactoryResource;
use App\Http\Resources\MhallResource;
use App\Http\Resources\MhallSharedResource;
use App\Http\Resources\SiloResource;
use App\Models\Factory;
use App\Models\Mhall;
use App\Models\Silo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class MhallController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Mhall::class);

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

        $query = Mhall::query()->with('factory', 'silo');
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $mhalls = MhallResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Nomenklature/Mhalls/MhallIndex', [
            'mhalls' => $mhalls,
            'filter' => $filter,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function show(Request $request): Response
    {
        Gate::authorize('viewAny', Mhall::class);

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

        $query = Mhall::query()->with(['factory', 'silo', 'mproductions']);
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $mhalls = MhallSharedResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Mproductions/Mhalls/Show', [
            'mhalls' => $mhalls,
            'filter' => $filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Mhall::class);

        return Inertia::render('Nomenklature/Mhalls/Create', [
            'factories' => FactoryResource::collection(Factory::all()),
            'silos' => SiloResource::collection(Silo::with(['factory'])->get()),
            'typeOptiont' => Mhall::TYPE_OPTIONS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMhallRequest $request): RedirectResponse
    {
        Gate::authorize('create', Mhall::class);

        Mhall::create([
            'name' => $request->name,
            'type' => $request->type,
            'factory_id' => $request->factory['id'],
            'silo_id' => $request->silo['id'],
        ]);

        return to_route('mhalls.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mhall $mhall): Response
    {
        Gate::authorize('update', $mhall);

        $mhall->load(['factory', 'silo']);

        return Inertia::render('Nomenklature/Mhalls/Edit', [
            'mhall' => new MhallResource($mhall),
            'factories' => FactoryResource::collection(Factory::all()),
            'silos' => SiloResource::collection(Silo::with(['factory'])->get()),
            'typeOptiont' => Mhall::TYPE_OPTIONS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateMhallRequest $request, Mhall $mhall): RedirectResponse
    {
        Gate::authorize('update', $mhall);

        $mhall->update([
            'name' => $request->name,
            'type' => $request->type,
            'factory_id' => $request->factory['id'],
            'silo_id' => $request->silo['id'],
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mhall $mhall): RedirectResponse
    {
        Gate::authorize('delete', $mhall);

        if ($mhall->mproductions()->exists()) {
            return back()->withErrors([
                'delete' => 'Не може да се изтрие Халето, защото има свързани производствени процеси.'
            ]);
        }

        $mhall->delete();

        return back();
    }
}
