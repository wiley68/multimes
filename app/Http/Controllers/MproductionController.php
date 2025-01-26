<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMproductionRequest;
use App\Http\Resources\MproductionsResource;
use App\Models\Mhall;
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

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,mhall_id,status,created_at',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
            'mhall' => 'nullable|integer|exists:mhalls,id',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 10;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'asc';
        $filter = $validated['filter'] ?? '';
        $mhall = $validated['mhall'] ?? null;

        $query = Mproduction::query()->with('mhall');
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }
        $mhall_name = null;
        if (!empty($mhall)) {
            $query->where('mhall_id', '=', (int)$mhall);
            $mhall_name = Mhall::findOrFail((int)$mhall)->name;
        }

        $mproductions = MproductionsResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Mproductions/MproductionIndex', [
            'mproductions' => $mproductions,
            'filter' => $filter,
            'mhall' => $mhall,
            'mhall_name' => $mhall_name,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMproductionRequest $request): RedirectResponse
    {
        Gate::authorize('create', Mproduction::class);

        switch ($request->mhall['type']) {
            case 'Ремонтни':
                $production_days = 20;
                break;
            case 'Заплождане':
                $production_days = 10;
                break;
            case 'Условна бременност':
                $production_days = 10;
                break;
            case 'Бременност':
                $production_days = 45;
                break;
            case 'Родилно':
                $production_days = 5;
                break;
            case 'Подрастване':
                $production_days = 20;
                break;
            default:
                $production_days = 20;
                break;
        }

        Mproduction::create([
            'status' => $request->status,
            'mhall_id' => $request->mhall['id'],
            'production_days' => $production_days,
        ]);

        return back();
    }

    /**
     * Display a resource.
     */
    public function show(Mproduction $mproduction): Response
    {
        Gate::authorize('view', $mproduction);

        $mproduction->load('mhall');

        return Inertia::render('Mproductions/Show', [
            'mproduction' => new MproductionsResource($mproduction),
        ]);
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
