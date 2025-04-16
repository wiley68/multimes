<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFactoryRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\FactoryResource;
use App\Models\City;
use App\Models\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class FactoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Factory::class);

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:0|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,city_id,name',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 50;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'asc';
        $filter = $validated['filter'] ?? '';

        $query = Factory::query()->with(['city', 'mhalls', 'uhalls']);
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $factories = FactoryResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Nomenklature/Factories/FactoryIndex', [
            'factories' => $factories,
            'filter' => $filter,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Factory::class);

        return Inertia::render('Nomenklature/Factories/Create', [
            'cities' => CityResource::collection(City::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFactoryRequest $request): RedirectResponse
    {
        Gate::authorize('create', Factory::class);

        Factory::create([
            'name' => $request->name,
            'city_id' => $request->city['id']
        ]);

        return to_route('factories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Factory $factory): Response
    {
        Gate::authorize('update', $factory);

        $factory->load('city');

        return Inertia::render('Nomenklature/Factories/Edit', [
            'factory' => new FactoryResource($factory),
            'cities' => CityResource::collection(City::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateFactoryRequest $request, Factory $factory): RedirectResponse
    {
        Gate::authorize('update', $factory);

        $factory->update([
            'name' => $request->name,
            'city_id' => $request->city['id']
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factory $factory): RedirectResponse
    {
        Gate::authorize('delete', $factory);

        if ($factory->mhalls()->exists()) {
            return back()->withErrors([
                'delete' => 'Не може да се изтрие Производствената база, защото има свързани халета за майки.'
            ]);
        }

        if ($factory->uhalls()->exists()) {
            return back()->withErrors([
                'delete' => 'Не може да се изтрие Производствената база, защото има свързани халета за угояване.'
            ]);
        }

        $factory->delete();

        return back();
    }
}
