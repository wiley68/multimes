<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMincrementRequest;
use App\Http\Resources\MdecrementResource;
use App\Http\Resources\MincrementResource;
use App\Http\Resources\MproductionsResource;
use App\Http\Resources\ProductResource;
use App\Models\Mincrement;
use App\Models\Mproduction;
use App\Models\Product;
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
    public function store(CreateMincrementRequest $request): RedirectResponse
    {
        Gate::authorize('create', Mincrement::class);

        $mproduction = Mproduction::findOrFail($request->mproduction_id);
        if ($mproduction !== null && (float)$request->quantity > (float)$mproduction->stock) {
            return back()->withErrors([
                'store' => 'Наличноста на продукта: [' . $mproduction->product->nomenklature . '] ' . $mproduction->product->name . ' [' . $mproduction->stock . '] е по-малка от предвидената за изписване в прихода Ви [' . $request->quantity . ']',
            ]);
        }

        Mincrement::create([
            'mproduction_id' => $request->mproduction_id,
            'product_id' => $request->product['id'],
            'quantity' => $request->quantity,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return to_route('mproductions.show', [
            "mproduction" => $request->mproduction_id,
            'tab' => 'increments',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mincrement $mincrement): Response|RedirectResponse
    {
        Gate::authorize('update', $mincrement);

        if ($mincrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен приход.'
            ]);
        }

        $mincrement->load(['product', 'mproduction']);
        $mproduction = $mincrement->mproduction;
        if ($mproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да редактирате приход към вече приключен процес!"
            ]);
        }

        return Inertia::render('Mproductions/Tabs/Increments/Edit', [
            'mincrement' => new MincrementResource($mincrement),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateMincrementRequest $request, Mincrement $mincrement): RedirectResponse
    {
        Gate::authorize('update', $mincrement);

        if ($mincrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен приход.'
            ]);
        }

        $mproduction = Mproduction::findOrFail($request->mproduction_id);
        if ($mproduction !== null && (float)$request->quantity > (float)$mproduction->stock) {
            return back()->withErrors([
                'update' => 'Наличноста на продукта: [' . $mproduction->product->nomenklature . '] ' . $mproduction->product->name . ' [' . $mproduction->stock . '] е по-малка от предвидената за изписване в прихода Ви [' . $request->quantity . ']',
            ]);
        }

        if ($mproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да редактирате приход към вече приключен процес!"
            ]);
        }

        $mincrement->update([
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return to_route('mproductions.show', [
            "mproduction" => $mincrement->mproduction_id,
            'tab' => 'increments',
        ]);
    }

    /**
     * Complete the specified resource in storage.
     */
    public function complete(CreateMincrementRequest $request, Mincrement $mincrement): RedirectResponse
    {
        Gate::authorize('update', $mincrement);

        if ($mincrement->status === 1) {
            return back()->withErrors([
                'update' => "Не можете да приключвате вече приключен приход!"
            ]);
        }

        $mproduction = $mincrement->mproduction;
        if ($mproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да приключвате приход към вече приключен процес!"
            ]);
        }

        $product = $mincrement->product;
        if (null !== $product) {
            $mproduction = Mproduction::findOrFail($request->mproduction_id);
            if ($mproduction !== null && (float)$request->quantity > (float)$mproduction->stock) {
                return back()->withErrors([
                    'update' => 'Наличноста на продукта: [' . $mproduction->product->nomenklature . '] ' . $mproduction->product->name . ' [' . $mproduction->stock . '] е по-малка от предвидената за изписване в прихода Ви [' . $request->quantity . ']',
                ]);
            }

            if ($mproduction->mhall->type === 'Ремонтни') {
                $zaplozdane = Product::where('type', '=', 'Прасета заплождане')->firstOrFail();
                $old_zaplozdane_stock = (float)$zaplozdane->stock;
                $new_zaplozdane_stock = (float)$old_zaplozdane_stock + (float)$request->quantity;
                $zaplozdane->stock = $new_zaplozdane_stock;
                $old_zaplozdane_price = (float)$zaplozdane->price;
                $new_zaplozdane_price = (($old_zaplozdane_price * $old_zaplozdane_stock) + ((float)$mproduction->price * (float)$request->quantity)) / ($old_zaplozdane_stock + (float)$request->quantity);
                $zaplozdane->price = $new_zaplozdane_price;
                $zaplozdane->save();
            }

            $new_stock = $mproduction->stock - $request->quantity;
            $new_price = $mproduction->price;
            if ($new_stock == 0) {
                $new_price = 0;
            }

            $mproduction->update([
                'stock' => $new_stock,
                'price' => $new_price,
            ]);
        }

        $mincrement->update([
            'status' => $request->status,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mincrement $mincrement): RedirectResponse
    {
        Gate::authorize('delete', $mincrement);

        if ($mincrement->status === 1) {
            return back()->withErrors([
                'update' => 'Не можете да променяте приключен приход.'
            ]);
        }

        $mproduction = $mincrement->mproduction;
        if ($mproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да изтривате приход от вече приключен процес!"
            ]);
        }

        $mincrement->delete();

        return back();
    }
}
