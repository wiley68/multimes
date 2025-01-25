<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUdecrementRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UdecrementResource;
use App\Models\Product;
use App\Models\Udecrement;
use App\Models\Uproduction;
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

        $uproduction = Uproduction::findOrFail($validated['uproduction_id']);
        if ($uproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да добавяте разход към вече приключен процес!"
            ]);
        }

        $products = Product::whereIn('type', ['Обща употреба'])->get();

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
            'uproduction' => $request->uproduction_id,
            'tab' => 'decrements',
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
        $uproduction = $udecrement->uproduction;
        if ($uproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да редактирате разход към вече приключен процес!"
            ]);
        }

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

        $uproduction = $udecrement->uproduction;
        if ($uproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да редактирате разход към вече приключен процес!"
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

        $uproduction = $udecrement->uproduction;
        if ($uproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да приключвате разход към вече приключен процес!"
            ]);
        }

        $product = $udecrement->product;
        if (null !== $product) {
            $new_price = (float)$udecrement->price;
            $new_quantity = (float)$udecrement->quantity;
            $current_quantity = (float)$product->stock;
            $current_price = (float)$product->price;
            $result_quantity = $current_quantity - $new_quantity;
            if ($result_quantity < 0) {
                return back()->withErrors([
                    'update' => 'Не можете да разходвате количеството ' . $new_quantity . '. Общото налично количество в склада ' . $current_quantity . ' е по-малко!'
                ]);
            }
            $result_price = ($current_price * $current_quantity + $new_price * $new_quantity) / ($current_quantity + $new_quantity);
            $product->update([
                'stock' => $result_quantity,
                'price' => $result_price,
            ]);
        }

        $udecrement->update([
            'status' => $request->status,
        ]);

        $udecrementTotal = $new_quantity * $new_price;
        $uproductionStock = (float)$uproduction->stock;
        $uproductionPrice = (float)$uproduction->price;
        $uproduction->price = $uproductionPrice + $udecrementTotal / $uproductionStock;
        $uproduction->save();

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

        $uproduction = $udecrement->uproduction;
        if ($uproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да изтривате разход от вече приключен процес!"
            ]);
        }

        $udecrement->delete();

        return back();
    }
}
