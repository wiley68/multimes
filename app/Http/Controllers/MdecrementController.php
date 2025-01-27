<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMdecrementRequest;
use App\Http\Resources\MdecrementResource;
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
    public function store(CreateMdecrementRequest $request): RedirectResponse
    {
        Gate::authorize('create', Mdecrement::class);

        Mdecrement::create([
            'mproduction_id' => $request->mproduction_id,
            'product_id' => $request->product['value'],
            'quantity' => $request->quantity,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return to_route('mproductions.show', [
            'mproduction' => $request->mproduction_id,
            'tab' => 'decrements',
        ]);
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
    public function edit(Mdecrement $mdecrement): Response|RedirectResponse
    {
        Gate::authorize('update', $mdecrement);

        if ($mdecrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен разход.'
            ]);
        }

        $mdecrement->load(['product', 'mproduction']);
        $mproduction = $mdecrement->mproduction;
        if ($mproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да редактирате разход към вече приключен процес!"
            ]);
        }

        return Inertia::render('Mproductions/Tabs/Decrements/Edit', [
            'mdecrement' => new MdecrementResource($mdecrement),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateMdecrementRequest $request, Mdecrement $mdecrement): RedirectResponse
    {
        Gate::authorize('update', $mdecrement);

        if ($mdecrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен разход.'
            ]);
        }

        $mproduction = $mdecrement->mproduction;
        if ($mproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да редактирате разход към вече приключен процес!"
            ]);
        }

        $mdecrement->update([
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return to_route('mproductions.show', [
            'mproduction' => $mdecrement->mproduction_id,
            'tab' => 'decrements',
        ]);
    }

    /**
     * Complete the specified resource in storage.
     */
    public function complete(CreateMdecrementRequest $request, Mdecrement $mdecrement): RedirectResponse
    {
        Gate::authorize('update', $mdecrement);

        if ($mdecrement->status === 1) {
            return back()->withErrors([
                'update' => "Не можете да приключвате вече приключен разход!"
            ]);
        }

        $mproduction = $mdecrement->mproduction;
        if ($mproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да приключвате разход към вече приключен процес!"
            ]);
        }

        $product = $mdecrement->product;
        if (null !== $product) {
            $new_price = (float)$mdecrement->price;
            $new_quantity = (float)$mdecrement->quantity;
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

        $mdecrement->update([
            'status' => $request->status,
        ]);

        $mdecrementTotal = $new_quantity * $new_price;
        $mproductionStock = (float)$mproduction->stock;
        $mproductionPrice = (float)$mproduction->price;
        $mproduction->price = $mproductionPrice + $mdecrementTotal / $mproductionStock;
        $mproduction->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mdecrement $mdecrement): RedirectResponse
    {
        Gate::authorize('delete', $mdecrement);

        if ($mdecrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен разход.'
            ]);
        }

        $mproduction = $mdecrement->mproduction;
        if ($mproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да изтривате разход от вече приключен процес!"
            ]);
        }

        $mdecrement->delete();

        return back();
    }
}
