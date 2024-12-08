<?php

namespace App\Http\Controllers;

use App\Http\Resources\MproductionsResource;
use App\Models\Mproduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class MproductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Mproduction::class);

        $rowsPerPage = $request->input('rowsPerPage', 10);
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'id') === null ? 'id' : $request->input('sortBy', 'id');
        $sortOrder = $request->input('sortOrder', 'asc');
        $filter = $request->input('filter', '');

        $query = Mproduction::query()->with('mhall');
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $mproductions = MproductionsResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Mproductions/MproductionIndex', [
            'mproductions' => $mproductions,
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
    public function show(Mproduction $mproduction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mproduction $mproduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mproduction $mproduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mproduction $mproduction)
    {
        //
    }
}
