<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Product::class);

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:0|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,nomenklature,name,type,stock,me,price',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 50;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'asc';
        $filter = $validated['filter'] ?? '';

        $query = Product::query();
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $products = ProductResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Nomenklature/Products/ProductIndex', [
            'products' => $products,
            'filter' => $filter,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Product::class);

        return Inertia::render('Nomenklature/Products/Create', [
            'typeOptions' => Product::TYPE_OPTIONS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request): RedirectResponse
    {
        Gate::authorize('create', Product::class);

        Product::create([
            'name' => $request->name,
            'nomenklature' => $request->nomenklature,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'me' => $request->me,
            'type' => $request->type,
        ]);

        return to_route('products.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): Response
    {
        Gate::authorize('update', $product);

        return Inertia::render('Nomenklature/Products/Edit', [
            'product' => new ProductResource($product),
            'typeOptions' => Product::TYPE_OPTIONS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateProductRequest $request, Product $product): RedirectResponse
    {
        Gate::authorize('update', $product);

        $product->update([
            'name' => $request->name,
            'nomenklature' => $request->nomenklature,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'me' => $request->me,
            'type' => $request->type,
        ]);

        return to_route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);

        if ($product->subdeliveries()->exists()) {
            return back()->withErrors([
                'delete' => 'Не може да се изтрие продукта, защото има свързани доставки.'
            ]);
        }

        $product->delete();

        return back();
    }
}
