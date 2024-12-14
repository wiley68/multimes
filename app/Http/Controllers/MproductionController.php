<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMproductionRequest;
use App\Http\Resources\MproductionsResource;
use App\Models\Mproduction;
use Illuminate\Http\RedirectResponse;
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
        $mhall = $request->input('mhall', '');

        $query = Mproduction::query()->with('mhall');
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }
        if (!empty($mhall)) {
            $query->where('mhall_id', '=', (int)$mhall);
        }

        $mproductions = MproductionsResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Mproductions/MproductionIndex', [
            'mproductions' => $mproductions,
            'filter' => $filter,
            'mhall' => $mhall,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMproductionRequest $request): RedirectResponse
    {
        Gate::authorize('create', Mproduction::class);

        Mproduction::create([
            'status' => $request->status,
            'mhall_id' => $request->mhall['id']
        ]);

        return back();
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
