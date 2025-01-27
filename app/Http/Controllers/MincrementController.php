<?php

namespace App\Http\Controllers;

use App\Http\Resources\MdecrementResource;
use App\Http\Resources\MincrementResource;
use App\Http\Resources\MproductionsResource;
use App\Http\Resources\ProductResource;
use App\Models\Mincrement;
use App\Models\Mproduction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class MincrementController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response|RedirectResponse
    {
        Gate::authorize('create', Mincrement::class);

        $validated = $request->validate([
            'mproduction_id' => 'required|integer',
        ]);

        $mproduction = Mproduction::findOrFail($validated['mproduction_id']);

        if ($mproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да добавяте приход към вече приключен процес!"
            ]);
        }

        if ($mproduction === null) {
            return back()->withErrors([
                'update' => 'Продукта не е открит в текущия процес!'
            ]);
        }

        $product = $mproduction->product;

        $mdecrements = $mproduction->mdecrements;
        $mdecrements->load(['product', 'mproduction']);
        $mincrements = $mproduction->mincrements;
        $mincrements->load(['product', 'mproduction']);

        return Inertia::render('Mproductions/Tabs/Increments/Create', [
            'mproduction' => new MproductionsResource($mproduction),
            'product' => new ProductResource($product),
            'mdecrements' => MdecrementResource::collection($mdecrements),
            'mincrements' => MincrementResource::collection($mincrements),
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
    public function show(Mincrement $mincrement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mincrement $mincrement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mincrement $mincrement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mincrement $mincrement)
    {
        //
    }
}
