<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUincrementRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UincrementResource;
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
     * Show the form for editing the specified resource.
     */
    public function edit(Uincrement $uincrement): Response|RedirectResponse
    {
        Gate::authorize('update', $uincrement);

        if ($uincrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен приход.'
            ]);
        }

        $uincrement->load(['product', 'uproduction']);

        return Inertia::render('Uproductions/Tabs/Increments/Edit', [
            'uincrement' => new UincrementResource($uincrement),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateUincrementRequest $request, Uincrement $uincrement): RedirectResponse
    {
        Gate::authorize('update', $uincrement);

        if ($uincrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен приход.'
            ]);
        }

        $uproduction = Uproduction::findOrFail($request->uproduction_id);
        if ($uproduction !== null && (float)$request->quantity > (float)$uproduction->stock) {
            return back()->withErrors([
                'update' => 'Наличноста на продукта: [' . $uproduction->product->nomenklature . '] ' . $uproduction->product->name . ' [' . $uproduction->stock . '] е по-малка от предвидената за изписване в прихода Ви [' . $request->quantity . ']',
            ]);
        }

        $uincrement->update([
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return to_route('uproductions.show', [
            "uproduction" => $uincrement->uproduction_id,
        ]);
    }

    /**
     * Complete the specified resource in storage.
     */
    public function complete(CreateUincrementRequest $request, Uincrement $uincrement): RedirectResponse
    {
        Gate::authorize('update', $uincrement);

        if ($uincrement->status === 1) {
            return back()->withErrors([
                'update' => "Не можете да приключвате вече приключен приход!"
            ]);
        }

        $product = $uincrement->product;
        if (null !== $product) {
            $uproduction = Uproduction::findOrFail($request->uproduction_id);
            if ($uproduction !== null && (float)$request->quantity > (float)$uproduction->stock) {
                return back()->withErrors([
                    'update' => 'Наличноста на продукта: [' . $uproduction->product->nomenklature . '] ' . $uproduction->product->name . ' [' . $uproduction->stock . '] е по-малка от предвидената за изписване в прихода Ви [' . $request->quantity . ']',
                ]);
            }

            $new_stock = $uproduction->stock - $request->quantity;
            $new_price = $uproduction->price;
            if ($new_stock == 0) {
                $new_price = 0;
            }

            $uproduction->update([
                'stock' => $new_stock,
                'price' => $new_price,
            ]);
        }

        $uincrement->update([
            'status' => $request->status,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uincrement $uincrement): RedirectResponse
    {
        Gate::authorize('delete', $uincrement);

        if ($uincrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен приход.'
            ]);
        }

        $uincrement->delete();

        return back();
    }
}
