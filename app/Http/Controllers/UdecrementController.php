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

        $products = Product::whereIn('type', ['Обща употреба', 'Силоз угояване'])->get();

        return Inertia::render('Uproductions/Tabs/Decrements/Create', [
            'uproduction_id' => $validated['uproduction_id'],
            'products' => ProductResource::collection($products),
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
     * Complete the specified resource in storage.
     */
    public function complete(CreateUdecrementRequest $request, Udecrement $udecrement): RedirectResponse
    {
        Gate::authorize('update', $udecrement);

        if ($udecrement->status === 1) {
            return back()->withErrors([
                'update' => "Не можете да приключвате вече приключен разход!"
            ]);
        }

        $product = $udecrement->product;
        if (null !== $product) {
            $silo = $udecrement->uproduction->uhall->silo;
            $new_price = (float)$udecrement->price;
            $new_quantity = (float)$udecrement->quantity;
            if ($silo?->product_id === $product->id) {
                $current_quantity = (float)$silo->stock;
                $current_price = (float)$silo->price;
                $result_quantity = $current_quantity - $new_quantity;
                if ($result_quantity < 0) {
                    return back()->withErrors([
                        'update' => "Не можете да разходвате количеството $new_quantity. Общото налично количество в силоза е по-малко!"
                    ]);
                }
                $result_price = ($current_price * $current_quantity + $new_price * $new_quantity) / ($current_quantity + $new_quantity);
                $silo->update([
                    'stock' => $result_quantity,
                    'price' => $result_price,
                ]);
            } else {
                $current_quantity = (float)$product->stock;
                $current_price = (float)$product->price;
                $result_quantity = $current_quantity - $new_quantity;
                if ($result_quantity < 0) {
                    return back()->withErrors([
                        'update' => "Не можете да разходвате количеството $new_quantity. Общото налично количество в склада е по-малко!"
                    ]);
                }
                $result_price = ($current_price * $current_quantity + $new_price * $new_quantity) / ($current_quantity + $new_quantity);
                $product->update([
                    'stock' => $result_quantity,
                    'price' => $result_price,
                ]);
            }
        }

        $udecrement->update([
            'status' => $request->status,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Udecrement $udecrement): RedirectResponse
    {
        Gate::authorize('delete', $udecrement);

        if ($udecrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен разход.'
            ]);
        }

        $udecrement->delete();

        return back();
    }
}
