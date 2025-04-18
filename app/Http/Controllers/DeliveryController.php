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
            'rowsPerPage' => 'integer|min:0|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,document,supplier,created_at,status',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 50;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'desc';
        $filter = $validated['filter'] ?? '';

        $query = Delivery::query()->with('subdeliveries');
        if (!empty($filter)) {
            $query->where('document', 'like', '%' . $filter . '%');
        }

        $deliveries = DeliveryResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Deliveries/DeliveryIndex', [
            'deliveries' => $deliveries,
            'filter' => $filter,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Delivery::class);

        $maxDocument = (int)Delivery::max('document') + 1;

        return Inertia::render('Deliveries/Create', [
            'maxDocument' => number_format($maxDocument),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDeliveryRequest $request): RedirectResponse
    {
        Gate::authorize('create', Delivery::class);

        $delivery = Delivery::create([
            'document' => $request->document,
            'supplier' => $request->supplier,
            'status' => $request->status['value'],
        ]);

        return to_route('deliveries.edit', [
            'delivery' => new DeliveryResource($delivery),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(Delivery $delivery): Response
    {
        Gate::authorize('view', $delivery);

        $delivery->load('subdeliveries');

        return Inertia::render('Deliveries/Show', [
            'delivery' => new DeliveryResource($delivery),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivery $delivery): Response|RedirectResponse
    {
        Gate::authorize('update', $delivery);

        if ($delivery->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключена доставка.'
            ]);
        }

        $delivery->load('subdeliveries');

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
     * Complete the specified resource in storage.
     */
    public function complete(CreateDeliveryRequest $request, Delivery $delivery): RedirectResponse
    {
        Gate::authorize('update', $delivery);

        $subdeliveries = $delivery->subdeliveries;
        if ($subdeliveries instanceof \Illuminate\Database\Eloquent\Collection && $subdeliveries->isNotEmpty()) {
            foreach ($subdeliveries as $subdelivery) {
                $product = $subdelivery->product;
                if (null !== $product) {
                    $current_price = (float)$product['price'];
                    $current_quantity = (float)$product['stock'];
                    $new_price = (float)$subdelivery['price'];
                    $new_quantity = (float)$subdelivery['quantity'];
                    $result_quantity = $current_quantity + $new_quantity;
                    $result_price = ($current_price * $current_quantity + $new_price * $new_quantity) / ($current_quantity + $new_quantity);
                    $product->update([
                        'stock' => $result_quantity,
                        'price' => $result_price,
                    ]);
                }
            }
        } else {
            return to_route('deliveries.index')->withErrors([
                'complete' => 'Не можете да запишете доставката. Липсват материали.'
            ]);
        }

        $delivery->update([
            'document' => $request->document,
            'supplier' => $request->supplier,
            'status' => $request->status['value'],
        ]);

        return to_route('deliveries.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery): RedirectResponse
    {
        Gate::authorize('delete', $delivery);

        if ($delivery->status === 1) {
            return back()->withErrors([
                'delete' => 'Не можете да изтривате приключена доставка.'
            ]);
        }

        $delivery->delete();

        return to_route('deliveries.index');
    }
}
