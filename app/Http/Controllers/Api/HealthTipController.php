<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthTip;
use App\Services\HealthTipService;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\HealthTipResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthTipController extends Controller
{
    protected HealthTipService $healthTipService;
    
    public function __construct(HealthTipService $healthTipService)
    {
        $this->healthTipService = $healthTipService;
    }
    public function index(): JsonResponse
    {
        $filters = request()->only(['category', 'source', 'search', 'limit', 'order_by', 'order_direction', 'published_from', 'published_to']);
        
        $result = $this->healthTipService->getHealthTips($filters);
        
        $statusCode = $result['success'] ? 200 : ($result['data'] === [] ? 404 : 500);
        
        return response()->json($result, $statusCode);
    }

    public function random(): JsonResponse
    {
        $result = $this->healthTipService->getRandomHealthTip();
        
        $statusCode = $result['success'] ? 200 : 404;
        
        return response()->json($result, $statusCode);
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

