<?php

namespace App\Http\Controllers;

use App\Http\Resources\MproductionsResource;
use App\Models\Mhall;
use App\Models\Mproduction;
use App\Models\Uhall;
use App\Models\Uproduction;
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
            'sortBy' => 'nullable|string|in:id,hall_id,group_number,partida_number,status,created_at,finished_at,stock,price',
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

        $mproductionQuery = Mproduction::query()
            ->selectRaw("
            id,
            mhall_id as hall_id,
            'M' as type,
            group_number,
            partida_number,
            status,
            created_at,
            finished_at,
            stock,
            price,
            product_id
        ")
            ->with(['mhall', 'product']);

        $uproductionQuery = Uproduction::query()
            ->selectRaw("
            id,
            uhall_id as hall_id,
            'U' as type,
            group_number,
            partida_number,
            status,
            created_at,
            finished_at,
            stock,
            price,
            product_id
        ")
            ->with(['uhall', 'product']);

        if (!empty($filter)) {
            $mproductionQuery->where('name', 'like', '%' . $filter . '%');
            $uproductionQuery->where('name', 'like', '%' . $filter . '%');
        }

        $hall_name = null;
        if (!empty($hall) && $type === 'M') {
            $mproductionQuery->where('mhall_id', '=', (int)$hall);
            $hall_name = Mhall::findOrFail((int)$hall)->name;
        }
        if (!empty($hall) && $type === 'U') {
            $uproductionQuery->where('uhall_id', '=', (int)$hall);
            $hall_name = Uhall::findOrFail((int)$hall)->name;
        }

        $query = $mproductionQuery->union($uproductionQuery);

        $query->orderBy($sortBy, $sortOrder);

        $productions = $query->paginate($rowsPerPage, ['*'], 'page', $page);

        return Inertia::render('Reports/ProductionsIndex', [
            'productions' => $productions,
            'filter' => $filter,
            'hall' => $hall,
            'hall_name' => $hall_name,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'type' => $type,
        ]);
    }
}
