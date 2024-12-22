<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubdeliveryRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SubdeliveryResource;
use App\Models\Product;
use App\Models\Subdelivery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class SubdeliveryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        Gate::authorize('create', Subdelivery::class);

        $validated = $request->validate([
            'delivery_id' => 'required|integer',
        ]);

        return Inertia::render('Deliveries/Subdeliveries/Create', [
            'delivery_id' => $validated['delivery_id'],
            'products' => ProductResource::collection(Product::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubdeliveryRequest $request): RedirectResponse
    {
        Gate::authorize('create', Subdelivery::class);

        Subdelivery::create([
            'delivery_id' => $request->delivery_id,
            'product_id' => $request->product['value'],
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return to_route('deliveries.edit', [
            "delivery" => $request->delivery_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subdelivery $subdelivery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subdelivery $subdelivery)
    {
        //
    }
}
