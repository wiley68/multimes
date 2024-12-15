<?php

namespace App\Http\Controllers;

use App\Http\Resources\MhallSharedResource;
use App\Http\Resources\UhallSharedResource;
use App\Models\Mhall;
use App\Models\Uhall;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $query_mhalls = Mhall::query()->with('mproductions');
        $mhalls = MhallSharedResource::collection($query_mhalls->get());

        $query_uhalls = Uhall::query()->with('uproductions');
        $uhalls = UhallSharedResource::collection($query_uhalls->get());

        return Inertia::render('Dashboard', [
            'mhalls' => $mhalls,
            'uhalls' => $uhalls,
        ]);
    }
}
