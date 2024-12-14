<?php

namespace App\Http\Controllers;

use App\Http\Resources\MhallSharedResource;
use App\Models\Mhall;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $query = Mhall::query()->with('mproductions');
        $mhalls = MhallSharedResource::collection($query->get());

        return Inertia::render('Dashboard', [
            'mhalls' => $mhalls,
        ]);
    }
}
