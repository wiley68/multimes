<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUdecrementRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UdecrementResource;
use App\Models\Product;
use App\Models\Udecrement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UdecrementController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        Gate::authorize('create', Udecrement::class);

        $validated = $request->validate([
            'uproduction_id' => 'required|integer',
        ]);

        return Inertia::render('Uproductions/Tabs/Decrements/Create', [
            'uproduction_id' => $validated['uproduction_id'],
            'products' => ProductResource::collection(Product::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUdecrementRequest $request): RedirectResponse
    {
        Gate::authorize('create', Udecrement::class);

        Udecrement::create([
            'uproduction_id' => $request->uproduction_id,
            'product_id' => $request->product['value'],
            'quantity' => $request->quantity,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return to_route('uproductions.show', [
            "uproduction" => $request->uproduction_id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Udecrement $udecrement): Response|RedirectResponse
    {
        Gate::authorize('update', $udecrement);

        if ($udecrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен разход.'
            ]);
        }

        $udecrement->load(['product', 'uproduction']);

        return Inertia::render('Uproductions/Tabs/Decrements/Edit', [
            'udecrement' => new UdecrementResource($udecrement),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateUdecrementRequest $request, Udecrement $udecrement): RedirectResponse
    {
        Gate::authorize('update', $udecrement);

        if ($udecrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен разход.'
            ]);
        }

        $udecrement->update([
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return to_route('uproductions.show', [
            "uproduction" => $udecrement->uproduction_id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Udecrement $udecrement)
    {
        //
    }
}
