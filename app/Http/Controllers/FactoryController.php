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
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'id') === null ? 'id' : $request->input('sortBy', 'id');
        $sortOrder = $request->input('sortOrder', 'asc');
        $filter = $request->input('filter', '');

        $query = Factory::query();
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $factories = FactoryResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Nomenklature/Factories/FactoryIndex', [
            'factories' => $factories,
            'filter' => $filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Nomenklature/Factories/Create', [
            'cities' => CityResource::collection(City::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFactoryRequest $request): RedirectResponse
    {
        Factory::create([
            'name' => $request->name,
            'city_id' => $request->city['id']
        ]);

        return to_route('factories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Factory $factory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Factory $factory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factory $factory)
    {
        //
    }
}