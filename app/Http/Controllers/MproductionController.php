<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMproductionRequest;
use App\Http\Requests\LoadMproductionRequest;
use App\Http\Resources\MdecrementResource;
use App\Http\Resources\MincrementResource;
use App\Http\Resources\MproductionsResource;
use App\Http\Resources\ProductResource;
use App\Models\Mdecrement;
use App\Models\Mhall;
use App\Models\Mproduction;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class MproductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Mproduction::class);

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,mhall_id,group_number,partida_number,status,created_at,finished_at,stock,price',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
            'mhall' => 'nullable|integer|exists:mhalls,id',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 10;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'desc';
        $filter = $validated['filter'] ?? '';
        $mhall = $validated['mhall'] ?? null;

        $query = Mproduction::query()->with('mhall', 'product', 'mdecrements', 'mincrements');
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }
        $mhall_name = null;
        if (!empty($mhall)) {
            $query->where('mhall_id', '=', (int)$mhall);
            $mhall_name = Mhall::findOrFail((int)$mhall)->name;
        }

        $mproductions = $query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page);

        return Inertia::render('Mproductions/MproductionIndex', [
            'mproductions' => MproductionsResource::collection($mproductions),
            'filter' => $filter,
            'mhall' => $mhall,
            'mhall_name' => $mhall_name,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMproductionRequest $request): RedirectResponse
    {
        Gate::authorize('create', Mproduction::class);

        switch ($request->mhall['type']) {
            case 'Ремонтни':
                $production_days = 20;
                break;
            case 'Заплождане':
                $production_days = 10;
                break;
            case 'Условна бременност':
                $production_days = 10;
                break;
            case 'Бременност':
                $production_days = 45;
                break;
            case 'Родилно':
                $production_days = 5;
                break;
            case 'Подрастване':
                $production_days = 20;
                break;
            default:
                $production_days = 20;
                break;
        }

        Mproduction::create([
            'status' => $request->status,
            'mhall_id' => $request->mhall['id'],
            'production_days' => $production_days,
            'group_number' => $request->group_number,
            'partida_number' => $request->partida_number,
        ]);

        return back();
    }

    /**
     * Display a resource.
     */
    public function show(Request $request, Mproduction $mproduction): Response
    {
        Gate::authorize('view', $mproduction);

        $mproduction->load('mhall', 'product');
        $mhall = $mproduction->mhall->load(['silo', 'factory']);
        $mhall->silo->load('product');
        $mhall->factory->load('city');
        $mdecrements = $mproduction->mdecrements()->orderBy('id', 'desc')->get();
        $mdecrements->load(['product', 'mproduction']);
        $mincrements = $mproduction->mincrements()->orderBy('id', 'desc')->get();
        $mincrements->load(['product', 'mproduction']);

        $tab = $request->tab ?? 'actions';

        return Inertia::render('Mproductions/Show', [
            'mproduction' => new MproductionsResource($mproduction),
            'mdecrements' => MdecrementResource::collection($mdecrements),
            'mincrements' => MincrementResource::collection($mincrements),
            'tab' => $tab,
        ]);
    }

    /**
     * Complete the specified resource in storage.
     */
    public function complete(Request $request, Mproduction $mproduction): RedirectResponse
    {
        Gate::authorize('update', $mproduction);

        $validated = $request->validate([
            'status' => 'required|integer|in:0',
            'rest' => 'required|numeric|min:0',
        ]);

        if ($mproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да приключвате вече приключен процес!"
            ]);
        }

        if ((float)$mproduction->stock > 0) {
            return back()->withErrors([
                'complete' => 'Във Вашия прозиводствен процес все още има налични прасета [' .
                    $mproduction->product->nomenklature . '] ' .
                    $mproduction->product->name . ' [' .
                    $mproduction->stock . ']. Не можете да приключите процеса докато все още имате налични прасета в него!',
            ]);
        }

        $furaz = optional($mproduction->mhall->silo->product)->type;

        $lastMdecrement = null;
        if ($furaz) {
            $lastMdecrement = $mproduction->mdecrements()
                ->whereHas('product', function ($query) use ($furaz) {
                    $query->where('type', $furaz)
                        ->where('status', 1);
                })
                ->orderBy('id', 'desc')
                ->first();
        }

        if ($lastMdecrement) {
            if ((float)$lastMdecrement->quantity >= (float)$validated['rest']) {
                $lastMdecrement->quantity -= (float)$validated['rest'];
                $lastMdecrement->save();

                $product = $lastMdecrement->product;
                $newstock = (float)$product->stock + (float)$validated['rest'];
                $siloPrice = optional($mproduction->mhall->silo)->price ?? 0;
                if ((float)$product->stock > 0) {
                    $product->price = (((float)$product->stock * (float)$product->price) + ((float)$validated['rest'] * (float)$siloPrice)) / $product->stock;
                } else {
                    if ($newstock > 0) {
                        $product->price = (float)$siloPrice;
                    } else {
                        $product->price = 0;
                    }
                }
                $product->stock = $newstock;
                $product->save();
            }
        }

        $silo = $mproduction->mhall->silo;
        $silo->update([
            'product_id' => 0,
            'stock' => 0,
            'price' => 0,
        ]);

        $mproduction->update([
            'status' => $validated['status'],
            'finished_at' => now(),
        ]);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function loading(Mproduction $mproduction): Response|RedirectResponse
    {
        Gate::authorize('update', $mproduction);

        if ($mproduction->status === 0) {
            return back()->withErrors([
                'update' => 'Не можете да зареждате процес който вече е приключен!'
            ]);
        }

        $mproduction->load(['mhall', 'product']);
        if ($mproduction->product_id !== 0) {
            $products = Product::where('id', '=', $mproduction->product_id);
        } else {
            switch ($mproduction->mhall['type']) {
                case 'Ремонтни':
                    $productType = 'Прасета ремонтни';
                    break;
                case 'Заплождане':
                    $productType = 'Прасета заплождане';
                    break;
                case 'Условна бременност':
                    $productType = 'Прасета условна бременност';
                    break;
                case 'Бременност':
                    $productType = 'Прасета бременност';
                    break;
                case 'Родилно':
                    $productType = 'Прасета родилно';
                    break;
                case 'Подрастване':
                    $productType = 'Прасета подрастване';
                    break;
                default:
                    $productType = '';
                    break;
            }
            $products = Product::where('type', '=', $productType);
        }

        return Inertia::render('Mproductions/Loading', [
            'mproduction' => new MproductionsResource($mproduction),
            'products' => ProductResource::collection($products->get()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function load(LoadMproductionRequest $request, Mproduction $mproduction): RedirectResponse
    {
        Gate::authorize('update', $mproduction);

        if ($mproduction->status === 0) {
            return back()->withErrors([
                'update' => 'Не можете да зареждате процес който вече е приключен!'
            ]);
        }

        $product = Product::findOrFail($request->product['id']);
        $new_quantity = (float)$request->stock;
        $current_quantity = (float)$mproduction->stock;
        $result_quantity = $current_quantity + $new_quantity;
        $new_price = (float)$product->price;
        $current_price = (float)$mproduction->price;
        $result_price = ($current_price * $current_quantity + $new_price * $new_quantity) / ($current_quantity + $new_quantity);

        if ((float)$product->stock < $new_quantity) {
            return back()->withErrors([
                'update' => 'Общата наличност в склада ' . $product->stock . ', е по-малка от тази която искате да прехвърлите ' . $new_quantity . '! Не можете да запишете промяната.'
            ]);
        }

        $mproduction->update([
            'product_id' => $request->product['id'],
            'stock' => $result_quantity,
            'price' => $result_price,
        ]);

        $product->stock = $product->stock - $new_quantity;
        $product->save();

        Mdecrement::create([
            'mproduction_id' => $mproduction->id,
            'product_id' => $request->product['id'],
            'quantity' => $new_quantity,
            'weight' => $request->weight,
            'price' => $new_price,
            'status' => 1,
        ]);

        return to_route('mproductions.show', [
            'mproduction' => $mproduction,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mproduction $mproduction)
    {
        Gate::authorize('delete', $mproduction);

        if ($mproduction->status === 1) {
            return back()->withErrors([
                'delete' => 'Не можете да изтривате приключена доставка.'
            ]);
        }

        $mproduction->delete();

        return to_route('mproductions.index');
    }
}
