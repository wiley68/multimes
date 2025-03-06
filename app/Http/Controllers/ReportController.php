<?php

namespace App\Http\Controllers;

use App\Http\Resources\MproductionsResource;
use App\Models\Mhall;
use App\Models\Mproduction;
use App\Models\Uhall;
use App\Models\Uproduction;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Mproduction::class);
        Gate::authorize('viewAny', Uproduction::class);

        $validated = $request->validate([
            'rowsPerPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'sortBy' => 'nullable|string|in:id,hall_name,group_number,partida_number,status,created_at,finished_at,stock,price',
            'sortOrder' => 'in:asc,desc',
            'filter' => 'nullable|string|max:255',
            'hall' => 'nullable|integer',
            'type' => 'nullable|string|in:M,U',
        ]);

        $rowsPerPage = $validated['rowsPerPage'] ?? 7;
        $page = $validated['page'] ?? 1;
        $sortBy = $validated['sortBy'] ?? 'id';
        $sortOrder = $validated['sortOrder'] ?? 'desc';
        $filter = $validated['filter'] ?? '';
        $hall = $validated['hall'] ?? null;
        $type = $validated['type'] ?? null;

        $mproductionQuery = DB::table('mproductions')
            ->selectRaw("
            mproductions.id,
            mhalls.name as hall_name,
            'M' as type,
            mproductions.group_number,
            mproductions.partida_number,
            mproductions.status,
            mproductions.created_at,
            mproductions.finished_at,
            mproductions.stock,
            mproductions.price,
            mproductions.product_id,
            products.name as product_name,
            products.nomenklature as product_nomenklature,
            (SELECT SUM(mincrements.price * mincrements.quantity) FROM mincrements WHERE mincrements.mproduction_id = mproductions.id GROUP BY mincrements.mproduction_id) AS increments_result,
            (SELECT SUM(mdecrements.price * mdecrements.quantity) FROM mdecrements WHERE mdecrements.mproduction_id = mproductions.id GROUP BY mdecrements.mproduction_id) AS decrements_result
        ")
            ->leftJoin('mhalls', 'mproductions.mhall_id', '=', 'mhalls.id')
            ->leftJoin('products', 'mproductions.product_id', '=', 'products.id');

        $uproductionQuery = DB::table('uproductions')
            ->selectRaw("
            uproductions.id,
            uhalls.name as hall_name,
            'U' as type,
            uproductions.group_number,
            uproductions.partida_number,
            uproductions.status,
            uproductions.created_at,
            uproductions.finished_at,
            uproductions.stock,
            uproductions.price,
            uproductions.product_id,
            products.name as product_name,
            products.nomenklature as product_nomenklature,
            (SELECT SUM(uincrements.price * uincrements.quantity) FROM uincrements WHERE uincrements.uproduction_id = uproductions.id GROUP BY uincrements.uproduction_id) AS increments_result,
            (SELECT SUM(udecrements.price * udecrements.quantity) FROM udecrements WHERE udecrements.uproduction_id = uproductions.id GROUP BY udecrements.uproduction_id) AS decrements_result
        ")
            ->leftJoin('uhalls', 'uproductions.uhall_id', '=', 'uhalls.id')
            ->leftJoin('products', 'uproductions.product_id', '=', 'products.id');

        if (!empty($filter)) {
            $mproductionQuery->where('mproductions.group_number', 'like', '%' . $filter . '%');
            $uproductionQuery->where('uproductions.group_number', 'like', '%' . $filter . '%');
        }

        if (!empty($hall)) {
            $mproductionQuery->where('mproductions.mhall_id', '=', (int)$hall);
            $uproductionQuery->where('uproductions.uhall_id', '=', (int)$hall);
        }

        $query = $mproductionQuery->union($uproductionQuery);

        $productions = DB::table(DB::raw("({$query->toSql()}) as merged"))
            ->mergeBindings($query)
            ->orderBy($sortBy, $sortOrder)
            ->paginate($rowsPerPage, ['*'], 'page', $page);

        return Inertia::render('Reports/ProductionsIndex', [
            'productions' => $productions,
            'filter' => $filter,
            'hall' => $hall,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'type' => $type,
        ]);
    }
}
