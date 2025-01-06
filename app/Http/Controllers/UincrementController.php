<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUincrementRequest;
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
    public function store(CreateUincrementRequest $request): RedirectResponse
    {
        Gate::authorize('create', Uincrement::class);

        $uproduction = Uproduction::findOrFail($request->uproduction_id);
        if ($uproduction !== null && (float)$request->quantity > (float)$uproduction->stock) {
            return back()->withErrors([
                'store' => 'Наличноста на продукта: [' . $uproduction->product->nomenklature . '] ' . $uproduction->product->name . ' [' . $uproduction->stock . '] е по-малка от предвидената за изписване в прихода Ви [' . $request->quantity . ']',
            ]);
        }

        Uincrement::create([
            'uproduction_id' => $request->uproduction_id,
            'product_id' => $request->product['id'],
            'quantity' => $request->quantity,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return to_route('uproductions.show', [
            "uproduction" => $request->uproduction_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Uincrement $uincrement)
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
