<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', City::class);

        $rowsPerPage = $request->input('rowsPerPage', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'id') === null ? 'id' : $request->input('sortBy', 'id');
        $sortOrder = $request->input('sortOrder', 'asc');
        $filter = $request->input('filter', '');

        $query = City::query();
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $cities = CityResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Nomenklature/Cities/CityIndex', [
            'cities' => $cities,
            'filter' => $filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', City::class);

        return Inertia::render('Nomenklature/Cities/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCityRequest $request): RedirectResponse
    {
        Gate::authorize('create', City::class);

        City::create([
            'name' => $request->name,
        ]);

        return to_route('cities.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city): Response
    {
        Gate::authorize('update', $city);

        return Inertia::render('Nomenklature/Cities/Edit', [
            'city' => new CityResource($city),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCityRequest $request, City $city): RedirectResponse
    {
        Gate::authorize('update', $city);

        $city->update([
            'name' => $request->name,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city): RedirectResponse
    {
        Gate::authorize('delete', $city);

        $city->delete();

        return back();
    }
}