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
            'sortBy' => 'nullable|string|in:id,name',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 10;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'desc';
        $filter = $validated['filter'] ?? '';

        $query = Product::query()->with(['silos', 'uproductions']);
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $stores = $query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page);

        return Inertia::render('Stores/StoreIndex', [
            'stores' => $stores,
            'filter' => $filter,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('view', Product::class);

        $product = Product::findOrFail($id);
        $silos = $product->silos;

        $stores[0]['nomenklature'] = $product['nomenklature'];
        $stores[0]['name'] = $product['name'];
        $stores[0]['quantity'] = $product['stock'];
        $stores[0]['me'] = $product['me'];
        $stores[0]['object'] = 'Склад';
        foreach ($silos as $key => $value) {
            $stores[$key + 1]['nomenklature'] = $product['nomenklature'];
            $stores[$key + 1]['name'] = $product['name'];
            $stores[$key + 1]['quantity'] = $value['stock'];
            $stores[$key + 1]['me'] = $product['me'];
            $stores[$key + 1]['object'] = $value['name'];
        }

        return Inertia::render('Stores/Show', [
            'stores' => $stores,
        ]);
    }
}
