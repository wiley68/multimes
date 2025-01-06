<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Uincrement;
use App\Models\Uproduction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UincrementController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response|RedirectResponse
    {
        Gate::authorize('create', Uincrement::class);

        $validated = $request->validate([
            'uproduction_id' => 'required|integer',
        ]);

        $uproduction = Uproduction::findOrFail($validated['uproduction_id']);

        if ($uproduction === null) {
            return back()->withErrors([
                'update' => 'Продукта не е открит в текущия процес!'
            ]);
        }

        $product = $uproduction->product;

        return Inertia::render('Uproductions/Tabs/Increments/Create', [
            'uproduction_id' => $validated['uproduction_id'],
            'product' => new ProductResource($product),
        ]);
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
    public function show(Uincement $uincement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Uincement $uincement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Uincement $uincement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uincement $uincement)
    {
        //
    }
}
