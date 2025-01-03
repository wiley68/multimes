<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
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
    public function create(Request $request): Response|RedirectResponse
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Udecrement $udecrement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Udecrement $udecrement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Udecrement $udecrement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Udecrement $udecrement)
    {
        //
    }
}
