<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUproductionRequest;
use App\Http\Resources\SiloResource;
use App\Http\Resources\UhallResource;
use App\Http\Resources\UproductionsResource;
use App\Models\Silo;
use App\Models\Uhall;
use App\Models\Uproduction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UproductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Uproduction::class);

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,uhall_id,status,created_at',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
            'uhall' => 'nullable|integer|exists:uhalls,id',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 10;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'asc';
        $filter = $validated['filter'] ?? '';
        $uhall = $validated['uhall'] ?? null;

        $query = Uproduction::query()->with('uhall');
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }
        $uhall_name = null;
        if (!empty($uhall)) {
            $query->where('uhall_id', '=', (int)$uhall);
            $uhall_name = Uhall::findOrFail((int)$uhall)->name;
        }

        $uproductions = UproductionsResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Uproductions/UproductionIndex', [
            'uproductions' => $uproductions,
            'filter' => $filter,
            'uhall' => $uhall,
            'uhall_name' => $uhall_name,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUproductionRequest $request): RedirectResponse
    {
        Gate::authorize('create', Uproduction::class);

        Uproduction::create([
            'status' => $request->status,
            'uhall_id' => $request->uhall['id']
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Uproduction $uproduction)
    {
        Gate::authorize('view', $uproduction);

        $uproduction->load('uhall');
        $uhall = Uhall::findOrFail($uproduction->uhall_id);
        $silo = Silo::findOrFail($uhall->silo_id)->load('product');

        return Inertia::render('Uproductions/Show', [
            'uproduction' => new UproductionsResource($uproduction),
            'silo' => new SiloResource($silo)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Uproduction $uproduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uproduction $uproduction)
    {
        //
    }
}
