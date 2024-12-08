<?php

namespace App\Http\Controllers;

use App\Http\Resources\FactoryResource;
use App\Http\Resources\UhallResource;
use App\Models\Factory;
use App\Models\Uhall;
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
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'id') === null ? 'id' : $request->input('sortBy', 'id');
        $sortOrder = $request->input('sortOrder', 'asc');
        $filter = $request->input('filter', '');

        $query = Uhall::query()->with('factory');
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $uhalls = UhallResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Mothers/Uhalls/UhallIndex', [
            'uhalls' => $uhalls,
            'filter' => $filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Mothers/Uhalls/Create', [
            'factories' => FactoryResource::collection(Factory::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Mhall::create([
            'name' => $request->name,
            'factory_id' => $request->factory['id']
        ]);

        return to_route('mhalls.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Uhall $uhall)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Uhall $uhall)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Uhall $uhall)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uhall $uhall)
    {
        //
    }
}
