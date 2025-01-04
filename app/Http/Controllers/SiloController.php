<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSiloRequest;
use App\Http\Requests\LoadSiloRequest;
use App\Http\Resources\FactoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SiloResource;
use App\Models\Factory;
use App\Models\Product;
use App\Models\Silo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class SiloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Silo::class);

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,factory_id,name',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 10;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'name';
        $sortOrder = $validated['sortOrder'] ?? 'asc';
        $filter = $validated['filter'] ?? '';

        $query = Silo::query()->with(['product', 'factory', 'mhalls', 'uhalls']);
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }

        $silos = SiloResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Nomenklature/Silos/SiloIndex', [
            'silos' => $silos,
            'filter' => $filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Silo::class);

        return Inertia::render('Nomenklature/Silos/Create', [
            'factories' => FactoryResource::collection(Factory::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSiloRequest $request): RedirectResponse
    {
        Gate::authorize('create', Silo::class);

        Silo::create([
            'name' => $request->name,
            'factory_id' => $request->factory['id'],
            'maxqt' => $request->maxqt,
        ]);

        return to_route('silos.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Silo $silo): Response
    {
        Gate::authorize('update', $silo);

        $silo->load('factory');

        return Inertia::render('Nomenklature/Silos/Edit', [
            'silo' => new SiloResource($silo),
            'factories' => FactoryResource::collection(Factory::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateSiloRequest $request, Silo $silo): RedirectResponse
    {
        Gate::authorize('update', $silo);

        if ((float)$silo->stock > (float)$request->maxqt) {
            return back()->withErrors([
                'update' => 'Наличноста в силоза е по-голяма от максимално допустимата! Не можете да запишете промяната.'
            ]);
        }

        $silo->update([
            'name' => $request->name,
            'factory_id' => $request->factory['id'],
            'maxqt' => $request->maxqt,
        ]);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function loading(Silo $silo, String $from, int $from_id): Response
    {
        Gate::authorize('update', $silo);

        $silo->load(['factory', 'product']);
        if ($silo->product_id !== 0) {
            $products = Product::where('id', '=', $silo->product_id);
        } else {
            if (count($silo->uhalls) > 0) {
                $products = Product::where('type', '=', 'Силоз угояване');
            } else {
                $products = Product::where('type', '=', 'Силоз майки');
            }
        }

        return Inertia::render('Nomenklature/Silos/Loading', [
            'silo' => new SiloResource($silo),
            'products' => ProductResource::collection($products->get()),
            'from' => $from,
            'from_id' => $from_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function load(LoadSiloRequest $request, Silo $silo, String $from, int $from_id): RedirectResponse
    {
        Gate::authorize('update', $silo);

        $product = Product::findOrFail($request->product['id']);
        $new_quantity = (float)$request->stock;
        $current_quantity = (float)$silo->stock;
        $result_quantity = $current_quantity + $new_quantity;
        $new_price = (float)$product->price;
        $current_price = (float)$silo->price;
        $result_price = ($current_price * $current_quantity + $new_price * $new_quantity) / ($current_quantity + $new_quantity);

        if ((float)$silo->maxqt < $result_quantity) {
            return back()->withErrors([
                'update' => 'Наличноста в силоза ще стане по-голяма от максимално допустимата! Не можете да запишете промяната.'
            ]);
        }

        if ((float)$product->stock < $new_quantity) {
            return back()->withErrors([
                'update' => 'Общата наличност е по-малка от тази която искате да прехвърлите! Не можете да запишете промяната.'
            ]);
        }

        $silo->update([
            'product_id' => $request->product['id'],
            'stock' => $result_quantity,
            'price' => $result_price,
        ]);

        $product->stock = $product->stock - $new_quantity;
        $product->save();

        if ($from === 'uproductions') {
            return to_route('uproductions.show', ['uproduction' => $from_id]);
        } else {
            return to_route('silos.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Silo $silo): RedirectResponse
    {
        Gate::authorize('delete', $silo);

        if ($silo->mhalls()->exists()) {
            return back()->withErrors([
                'delete' => 'Не може да се изтрие Силоза, защото има свързани халета за майки.'
            ]);
        }

        if ($silo->uhalls()->exists()) {
            return back()->withErrors([
                'delete' => 'Не може да се изтрие Силоза, защото има свързани халета за угояване.'
            ]);
        }

        $silo->delete();

        return back();
    }
}
