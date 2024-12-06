<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CityController extends Controller
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
        return Inertia::render('Nomenklature/Cities/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCityRequest $request): RedirectResponse
    {
        City::create([
            'name' => $request->name,
        ]);

        return to_route('cities.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $city = City::findById($id);

        return Inertia::render('Nomenklature/Cities/Edit', [
            'city' => new CityResource($city),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCityRequest $request, string $id): RedirectResponse
    {
        $city = City::findById($id);
        $city->update([
            'name' => $request->name,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $city = City::findById($id);
        $city->delete();

        return back();
    }
}
