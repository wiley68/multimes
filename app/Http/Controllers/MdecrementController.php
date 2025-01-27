<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Mdecrement;
use App\Models\Mproduction;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class MdecrementController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response|RedirectResponse
    {
        Gate::authorize('create', Mdecrement::class);

        $validated = $request->validate([
            'mproduction_id' => 'required|integer',
        ]);

        $mproduction = Mproduction::findOrFail($validated['mproduction_id']);
        if ($mproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да добавяте разход към вече приключен процес!"
            ]);
        }

        $products = Product::whereIn('type', ['Обща употреба'])->get();

        return Inertia::render('Mproductions/Tabs/Decrements/Create', [
            'mproduction_id' => $validated['mproduction_id'],
            'products' => ProductResource::collection($products),
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
    public function show(Mdecrement $mdecrement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mdecrement $mdecrement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mdecrement $mdecrement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mdecrement $mdecrement)
    {
        //
    }
}
