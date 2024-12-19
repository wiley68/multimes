<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDeliveryRequest;
use App\Http\Resources\DeliveryResource;
use App\Models\Delivery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Delivery::class);

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,document,supplier,created_at,status',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 10;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'asc';
        $filter = $validated['filter'] ?? '';

        $query = Delivery::query();
        if (!empty($filter)) {
            $query->where('document', 'like', '%' . $filter . '%');
        }

        $deliveries = DeliveryResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Deliveries/DeliveryIndex', [
            'deliveries' => $deliveries,
            'filter' => $filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Delivery::class);

        return Inertia::render('Deliveries/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDeliveryRequest $request): RedirectResponse
    {
        Gate::authorize('create', Delivery::class);

        Delivery::create([
            'document' => $request->document,
            'supplier' => $request->supplier,
            'status' => $request->status['value'],
        ]);

        return to_route('deliveries.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivery $delivery): Response
    {
        Gate::authorize('update', $delivery);

        return Inertia::render('Deliveries/Edit', [
            'delivery' => new DeliveryResource($delivery),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateDeliveryRequest $request, Delivery $delivery): RedirectResponse
    {
        Gate::authorize('update', $delivery);

        $delivery->update([
            'document' => $request->document,
            'supplier' => $request->supplier,
            'status' => $request->status['value'],
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery): RedirectResponse
    {
        Gate::authorize('delete', $delivery);

        if ($delivery->status === 1) {
            return back()->withErrors([
                'delete' => 'Не може да се изтрие доставката, защото е приключена.'
            ]);
        }

        $delivery->delete();

        return back();
    }
}
