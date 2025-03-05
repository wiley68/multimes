<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Product::class);

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,name,nomenklature,type,price,stock,me',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 10;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'asc';
        $filter = $validated['filter'] ?? '';

        $query = Product::query()->with(['silos', 'uproductions', 'mproductions']);
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $stores = $query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page);

        return Inertia::render('Stores/StoreIndex', [
            'stores' => $stores,
            'filter' => $filter,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('view', Product::class);

        $stores = [];
        $key = 0;
        $product = Product::findOrFail($id);
        $silos = $product->silos;
        $uproductions = $product->uproductions;
        $mproductions = $product->mproductions;

        if ((int)$product['stock'] !== 0) {
            $stores[0]['nomenklature'] = $product['nomenklature'];
            $stores[0]['name'] = $product['name'];
            $stores[0]['type'] = $product['type'];
            $stores[0]['quantity'] = $product['stock'];
            $stores[0]['me'] = $product['me'];
            $stores[0]['object'] = 'Склад';
            $key = 1;
        }
        foreach ($silos as $silo) {
            $stores[$key]['nomenklature'] = $product['nomenklature'];
            $stores[$key]['name'] = $product['name'];
            $stores[$key]['type'] = $product['type'];
            $stores[$key]['quantity'] = $silo['stock'];
            $stores[$key]['me'] = $product['me'];
            $stores[$key]['object'] = 'Силоз: ' . $silo['name'];
            $key++;
        }
        foreach ($uproductions as $uproduction) {
            $stores[$key]['nomenklature'] = $product['nomenklature'];
            $stores[$key]['name'] = $product['name'];
            $stores[$key]['type'] = $product['type'];
            $stores[$key]['quantity'] = $uproduction['stock'];
            $stores[$key]['me'] = $product['me'];
            $stores[$key]['object'] = 'Продукционен процес: ' . $uproduction['id'];
            $key++;
        }
        foreach ($mproductions as $mproduction) {
            $stores[$key]['nomenklature'] = $product['nomenklature'];
            $stores[$key]['name'] = $product['name'];
            $stores[$key]['type'] = $product['type'];
            $stores[$key]['quantity'] = $mproduction['stock'];
            $stores[$key]['me'] = $product['me'];
            $stores[$key]['object'] = 'Продукционен процес: ' . $mproduction['id'];
            $key++;
        }

        return Inertia::render('Stores/Show', [
            'stores' => $stores,
        ]);
    }
}
