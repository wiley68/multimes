<?php

namespace App\Http\Controllers;

use App\Http\Resources\MhallResource;
use App\Models\Mhall;
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

        $query = Mhall::query()->with('factories');
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
    public function create()
    {
        //
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
    public function show(Mhall $mhall)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mhall $mhall)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mhall $mhall)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mhall $mhall)
    {
        //
    }
}
