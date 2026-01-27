<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthTip;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\HealthTipResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthTipController extends Controller
{
    public function index(): JsonResponse
    {
        $query = HealthTip::query();
        
        // Filter by category if provided
        if (request()->has('category')) {
            $query->where('category', request('category'));
        }
        
        // Filter by source if provided
        if (request()->has('source')) {
            $query->where('source', request('source'));
        }
        
        // Filter by search term if provided
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        return response()->json($query->get());
    }

    public function random(): JsonResponse
    {
        return Cache::remember('random_health_tip', now()->addMinutes(30), function () {
            $tip = HealthTip::inRandomOrder()->first();
            
            if (!$tip) {
                return response()->json(['message' => 'No health tips available'], 404);
            }
            
            return response()->json(new HealthTipResource($tip));
        });
    }

    public function categories(): JsonResponse
    {
        $categories = HealthTip::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category');
            
        return response()->json($categories);
    }

    public function sources(): JsonResponse
    {
        $sources = HealthTip::select('source')
            ->distinct()
            ->whereNotNull('source')
            ->orderBy('source')
            ->pluck('source');
            
        return response()->json($sources);
    }
}

