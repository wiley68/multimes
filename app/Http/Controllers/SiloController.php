<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSiloRequest;
use App\Http\Requests\LoadSiloRequest;
use App\Http\Resources\FactoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SiloResource;
use App\Models\Factory;
use App\Models\Mdecrement;
use App\Models\Mproduction;
use App\Models\Product;
use App\Models\Silo;
use App\Models\Udecrement;
use App\Models\Uproduction;
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
        $sortBy = $validated['sortBy'] ?? 'id';
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
    public function loading(Silo $silo, int $uproduction_id): Response|RedirectResponse
    {
        Gate::authorize('update', $silo);

        $uproduction = Uproduction::findOrFail($uproduction_id);
        if ($uproduction->status === 0) {
            return back()->withErrors([
                'update' => 'Не можете да зареждате процес който вече е приключен!'
            ]);
        }

        $silo->load(['factory', 'product']);
        if ($silo->product_id !== 0) {
            $products = Product::where('id', '=', $silo->product_id);
        } else {
            if (count($silo->uhalls) > 0) {
                $products = Product::where('type', '=', 'Фураж угояване');
            } else {
                $products = Product::where('type', '=', 'Силоз майки');
            }
        }

        return Inertia::render('Nomenklature/Silos/Loading', [
            'silo' => new SiloResource($silo),
            'products' => ProductResource::collection($products->get()),
            'uproduction' => $uproduction_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function load(LoadSiloRequest $request, Silo $silo, int $uproduction_id): RedirectResponse
    {
        Gate::authorize('update', $silo);

        $uproduction = Uproduction::findOrFail($uproduction_id);
        if ($uproduction->status === 0) {
            return back()->withErrors([
                'update' => 'Не можете да зареждате процес който вече е приключен!'
            ]);
        }

        $product = Product::findOrFail($request->product['id']);
        $new_quantity = (float)$request->stock;
        $new_price = (float)$product->price;

        if ((float)$silo->maxqt < $new_quantity) {
            return back()->withErrors([
                'update' => 'Максимално допустимото количество е: ' . $silo->maxqt . '! Не можете да го надхвърляте.'
            ]);
        }

        if ((float)$product->stock < $new_quantity) {
            return back()->withErrors([
                'update' => 'Общата наличност ' . $product->stock . ' е по-малка от тази която искате да прехвърлите ' . $new_quantity . '! Не можете да запишете промяната.'
            ]);
        }

        $silo->update([
            'product_id' => $request->product['id'],
            'stock' => $new_quantity,
            'price' => $new_price,
        ]);

        $product->stock = $product->stock - $new_quantity;
        $product->save();

        Udecrement::create([
            'uproduction_id' => $uproduction_id,
            'product_id' => $request->product['id'],
            'quantity' => $new_quantity,
            'price' => $new_price,
            'status' => 1,
        ]);

        $udecrements = $uproduction->udecrements;
        $totalDecrements = 0;
        foreach ($udecrements as $item) {
            $totalDecrements += (float)$item['quantity'] * (float)$item['price'];
        }
        if ((float)$uproduction->stock == 0) {
            $uproduction->price = 0;
        } else {
            $uproduction->price = $totalDecrements / (float)$uproduction->stock;
        }
        $uproduction->save();

        return to_route('uproductions.show', [
            'uproduction' => $uproduction_id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function mloading(Silo $silo, int $mproduction_id): Response|RedirectResponse
    {
        Gate::authorize('update', $silo);

        $mproduction = Mproduction::findOrFail($mproduction_id);
        if ($mproduction->status === 0) {
            return back()->withErrors([
                'update' => 'Не можете да зареждате процес който вече е приключен!'
            ]);
        }

        $silo->load(['factory', 'product']);
        if ($silo->product_id !== 0) {
            $products = Product::where('id', '=', $silo->product_id);
        } else {
            switch ($mproduction->mhall['type']) {
                case 'Ремонтни':
                    $productType = 'Фураж ремонтни';
                    break;
                case 'Заплождане':
                    $productType = 'Фураж бременни';
                    break;
                case 'Условна бременност':
                    $productType = 'Фураж бременни';
                    break;
                case 'Бременност':
                    $productType = 'Фураж бременни';
                    break;
                case 'Родилно':
                    $productType = 'Фураж кърмачки';
                    break;
                case 'Подрастване':
                    $productType = 'Фураж угояване';
                    break;
                default:
                    $productType = '';
                    break;
            }
            $products = Product::where('type', '=', $productType);
        }

        return Inertia::render('Nomenklature/Silos/Mloading', [
            'silo' => new SiloResource($silo),
            'products' => ProductResource::collection($products->get()),
            'mproduction' => $mproduction_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function mload(LoadSiloRequest $request, Silo $silo, int $mproduction_id): RedirectResponse
    {
        Gate::authorize('update', $silo);

        $mproduction = Mproduction::findOrFail($mproduction_id);
        if ($mproduction->status === 0) {
            return back()->withErrors([
                'update' => 'Не можете да зареждате процес който вече е приключен!'
            ]);
        }

        $product = Product::findOrFail($request->product['id']);
        $new_quantity = (float)$request->stock;
        $new_price = (float)$product->price;

        if ((float)$silo->maxqt < $new_quantity) {
            return back()->withErrors([
                'update' => 'Максимално допустимото количество е: ' . $silo->maxqt . '! Не можете да го надхвърляте.'
            ]);
        }

        if ((float)$product->stock < $new_quantity) {
            return back()->withErrors([
                'update' => 'Общата наличност ' . $product->stock . ' е по-малка от тази която искате да прехвърлите ' . $new_quantity . '! Не можете да запишете промяната.'
            ]);
        }

        $silo->update([
            'product_id' => $request->product['id'],
            'stock' => $new_quantity,
            'price' => $new_price,
        ]);

        $product->stock = $product->stock - $new_quantity;
        $product->save();

        Mdecrement::create([
            'mproduction_id' => $mproduction_id,
            'product_id' => $request->product['id'],
            'quantity' => $new_quantity,
            'price' => $new_price,
            'status' => 1,
        ]);

        $mdecrements = $mproduction->mdecrements;
        $totalDecrements = 0;
        foreach ($mdecrements as $item) {
            $totalDecrements += (float)$item['quantity'] * (float)$item['price'];
        }
        if ((float)$mproduction->stock == 0) {
            $mproduction->price = 0;
        } else {
            $mproduction->price = $totalDecrements / (float)$mproduction->stock;
        }

        $mproduction->save();

        return to_route('mproductions.show', [
            'mproduction' => $mproduction_id,
        ]);
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
