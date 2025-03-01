<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUproductionRequest;
use App\Http\Requests\LoadUproductionRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UdecrementResource;
use App\Http\Resources\UincrementResource;
use App\Http\Resources\UproductionsResource;
use App\Models\Product;
use App\Models\Udecrement;
use App\Models\Uhall;
use App\Models\Uproduction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UproductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Uproduction::class);

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,uhall_id,status,group_number,partida_number,created_at',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
            'uhall' => 'nullable|integer|exists:uhalls,id',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 10;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'desc';
        $filter = $validated['filter'] ?? '';
        $uhall = $validated['uhall'] ?? null;

        $query = Uproduction::query()->with(['uhall', 'product', 'udecrements', 'uincrements']);
        if (!empty($filter)) {
            $query->where('name', 'like', '%' . $filter . '%');
        }
        $uhall_name = null;
        if (!empty($uhall)) {
            $query->where('uhall_id', '=', (int)$uhall);
            $uhall_name = Uhall::findOrFail((int)$uhall)->name;
        }

        $uproductions = UproductionsResource::collection($query->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page));

        return Inertia::render('Uproductions/UproductionIndex', [
            'uproductions' => $uproductions,
            'filter' => $filter,
            'uhall' => $uhall,
            'uhall_name' => $uhall_name,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUproductionRequest $request): RedirectResponse
    {
        Gate::authorize('create', Uproduction::class);

        Uproduction::create([
            'status' => $request->status,
            'uhall_id' => $request->uhall['id'],
            'production_days' => $request->production_days,
            'group_number' => $request->group_number,
            'partida_number' => $request->partida_number,
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Uproduction $uproduction)
    {
        Gate::authorize('view', $uproduction);

        $uproduction->load(['uhall', 'product']);
        $uhall = $uproduction->uhall->load(['silo', 'factory']);
        $uhall->silo->load('product');
        $uhall->factory->load('city');
        $udecrements = $uproduction->udecrements()->orderBy('id', 'desc')->get();
        $udecrements->load(['product', 'uproduction']);
        $uincrements = $uproduction->uincrements()->orderBy('id', 'desc')->get();
        $uincrements->load(['product', 'uproduction']);

        $tab = $request->tab ?? 'actions';

        return Inertia::render('Uproductions/Show', [
            'uproduction' => new UproductionsResource($uproduction),
            'udecrements' => UdecrementResource::collection($udecrements),
            'uincrements' => UincrementResource::collection($uincrements),
            'tab' => $tab,
        ]);
    }

    /**
     * Complete the specified resource in storage.
     */
    public function complete(Request $request, Uproduction $uproduction): RedirectResponse
    {
        Gate::authorize('update', $uproduction);

        $validated = $request->validate([
            'status' => 'required|integer|in:0',
            'rest' => 'required|numeric|min:0',
        ]);

        if ($uproduction->status === 0) {
            return back()->withErrors([
                'complete' => "Не можете да приключвате вече приключен процес!"
            ]);
        }

        if ((float)$uproduction->stock > 0) {
            return back()->withErrors([
                'complete' => 'Във Вашия прозиводствен процес все още има налични прасета [' .
                    $uproduction->product->nomenklature . '] ' .
                    $uproduction->product->name . ' [' .
                    $uproduction->stock . ']. Не можете да приключите процеса докато все още имате налични прасета в него!',
            ]);
        }

        $furaz = optional($uproduction->uhall->silo->product)->type;
        $lastUdecrement = null;
        if ($furaz) {
            $lastUdecrement = $uproduction->udecrements()
                ->whereHas('product', function ($query) use ($furaz) {
                    $query->where('type', $furaz)
                        ->where('status', 1);
                })
                ->orderBy('id', 'desc')
                ->first();
        }

        if (!$lastUdecrement) {
            return back()->withErrors([
                'complete' => 'В силоза не е зареждан фураж. Операцията не може да се извърши.',
            ]);
        }

        if ((float)$lastUdecrement->quantity < (float)$validated['rest']) {
            return back()->withErrors([
                'complete' => 'Количеството фураж зареден в последния процес [' .
                    $lastUdecrement->quantity . '] е по-малко от остатъчното количество фураж в силоза [' .
                    $validated['rest'] . ']. Операцията не може да се извърши.',
            ]);
        }

        $lastUdecrement->quantity -= (float)$validated['rest'];
        $lastUdecrement->save();

        $product = $lastUdecrement->product;
        $product->stock = (float)$product->stock + (float)$validated['rest'];
        if ((float)$product->stock === 0.00) {
            $product->price = 0;
        } else {
            $siloPrice = optional($uproduction->uhall->silo)->price ?? 0;
            $product->price = ((float)$product->stock * (float)$product->price + (float)$validated['rest'] * (float)$siloPrice) / $product->stock;
        }
        $product->save();

        $silo = $uproduction->uhall->silo;
        $silo->update([
            'product_id' => 0,
            'stock' => 0,
            'price' => 0,
        ]);

        $uproduction->update([
            'status' => $validated['status'],
            'finished_at' => now(),
        ]);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function loading(Uproduction $uproduction): Response|RedirectResponse
    {
        Gate::authorize('update', $uproduction);

        if ($uproduction->status === 0) {
            return back()->withErrors([
                'update' => 'Не можете да зареждате процес който вече е приключен!'
            ]);
        }

        $uproduction->load(['uhall', 'product']);
        if ($uproduction->product_id !== 0) {
            $products = Product::where('id', '=', $uproduction->product_id);
        } else {
            $products = Product::where('type', '=', 'Прасета угояване');
        }

        return Inertia::render('Uproductions/Loading', [
            'uproduction' => new UproductionsResource($uproduction),
            'products' => ProductResource::collection($products->get()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function load(LoadUproductionRequest $request, Uproduction $uproduction): RedirectResponse
    {
        Gate::authorize('update', $uproduction);

        if ($uproduction->status === 0) {
            return back()->withErrors([
                'update' => 'Не можете да зареждате процес който вече е приключен!'
            ]);
        }

        $product = Product::findOrFail($request->product['id']);
        $new_quantity = (float)$request->stock;
        $current_quantity = (float)$uproduction->stock;
        $result_quantity = $current_quantity + $new_quantity;
        $new_price = (float)$product->price;
        $current_price = (float)$uproduction->price;
        $result_price = ($current_price * $current_quantity + $new_price * $new_quantity) / ($current_quantity + $new_quantity);

        if ((float)$product->stock < $new_quantity) {
            return back()->withErrors([
                'update' => 'Общата наличност в склада ' . $product->stock . ', е по-малка от тази която искате да прехвърлите ' . $new_quantity . '! Не можете да запишете промяната.'
            ]);
        }

        $uproduction->update([
            'product_id' => $request->product['id'],
            'stock' => $result_quantity,
            'price' => $result_price,
        ]);

        $product->stock = $product->stock - $new_quantity;
        $product->save();

        Udecrement::create([
            'uproduction_id' => $uproduction->id,
            'product_id' => $request->product['id'],
            'quantity' => $new_quantity,
            'weight' => $request->weight,
            'price' => $new_price,
            'status' => 1,
        ]);

        return to_route('uproductions.show', [
            'uproduction' => $uproduction,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Uproduction $uproduction): RedirectResponse
    {
        Gate::authorize('delete', $uproduction);

        if ($uproduction->status === 1 && (float)$uproduction->stock !== 0.00 && (int)$uproduction->product_id !== 0) {
            return back()->withErrors([
                'delete' => 'Не можете да изтривате активен процес или процес с налични прасета.'
            ]);
        }

        $uproduction->delete();

        return to_route('uproductions.index');
    }
}
