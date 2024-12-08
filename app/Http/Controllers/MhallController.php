<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMhallRequest;
use App\Http\Resources\FactoryResource;
use App\Http\Resources\MhallResource;
use App\Models\Factory;
use App\Models\Mhall;
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
        $rowsPerPage = $request->input('rowsPerPage', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'id') === null ? 'id' : $request->input('sortBy', 'id');
        $sortOrder = $request->input('sortOrder', 'asc');
        $filter = $request->input('filter', '');

        $query = Mhall::query()->with('factory');
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $mhalls = MhallResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Mothers/Mhalls/MhallIndex', [
            'mhalls' => $mhalls,
            'filter' => $filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Mothers/Mhalls/Create', [
            'factories' => FactoryResource::collection(Factory::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMhallRequest $request): RedirectResponse
    {
        Mhall::create([
            'name' => $request->name,
            'factory_id' => $request->factory['id']
        ]);

        return to_route('mhalls.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mhall $mhall)
    {
        $mhall->load('factory');

        return Inertia::render('Mothers/Mhalls/Edit', [
            'mhall' => new MhallResource($mhall),
            'factories' => FactoryResource::collection(Factory::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateMhallRequest $request, Mhall $mhall): RedirectResponse
    {
        $mhall->update([
            'name' => $request->name,
            'factory_id' => $request->factory['id']
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mhall $mhall): RedirectResponse
    {
        $mhall->delete();

        return back();
    }
}
