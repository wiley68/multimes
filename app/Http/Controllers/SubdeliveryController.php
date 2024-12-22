<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubdeliveryRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SubdeliveryResource;
use App\Models\Delivery;
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
    public function create(Request $request): Response|RedirectResponse
    {
        Gate::authorize('create', Subdelivery::class);

        $validated = $request->validate([
            'delivery_id' => 'required|integer',
        ]);

        $delivery = Delivery::findOrFail($validated['delivery_id']);
        if ($delivery->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключена доставка.'
            ]);
        }

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

        $delivery = Delivery::findOrFail($request->delivery_id);
        if ($delivery->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключена доставка.'
            ]);
        }

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
     * Show the form for creating a new resource.
     */
    public function edit(Subdelivery $subdelivery): Response|RedirectResponse
    {
        Gate::authorize('update', $subdelivery);

        $delivery = $subdelivery->delivery()->first();
        if ($delivery->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключена доставка.'
            ]);
        }

        return Inertia::render('Deliveries/Subdeliveries/Edit', [
            'subdelivery' => new SubdeliveryResource($subdelivery),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateSubdeliveryRequest $request, Subdelivery $subdelivery): RedirectResponse
    {
        Gate::authorize('update', $subdelivery);

        $delivery = $subdelivery->delivery()->first();
        if ($delivery->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключена доставка.'
            ]);
        }

        $subdelivery->update([
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return to_route('deliveries.edit', [
            "delivery" => $subdelivery->delivery_id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subdelivery $subdelivery): RedirectResponse
    {
        Gate::authorize('delete', $subdelivery);

        $delivery = $subdelivery->delivery()->first();
        if ($delivery->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключена доставка.'
            ]);
        }

        $subdelivery->delete();

        return back();
    }
}
