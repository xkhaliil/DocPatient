<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthTip;
use App\Services\HealthTipService;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\HealthTipResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HealthTipController extends Controller
{
    protected HealthTipService $healthTipService;
    
    public function __construct(HealthTipService $healthTipService)
    {
        $this->healthTipService = $healthTipService;
    }
    
    /**
     * Display a listing of the health tips.
     */
    public function index(): JsonResponse
    {
        $filters = request()->only(['category', 'source', 'search', 'limit', 'order_by', 'order_direction', 'published_from', 'published_to']);
        
        $result = $this->healthTipService->getHealthTips($filters);
        
        $statusCode = $result['success'] ? 200 : ($result['data'] === [] ? 404 : 500);
        
        return response()->json($result, $statusCode);
    }

    /**
     * Display a specific health tip.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $healthTip = HealthTip::find($id);
            
            if (!$healthTip) {
                return response()->json([
                    'success' => false,
                    'message' => 'Health tip not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => new HealthTipResource($healthTip)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving health tip: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created health tip.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Debug: Log what data is being received
            \Log::info('Store request data:', $request->all());
            \Log::info('Request headers:', $request->headers->all());
            \Log::info('Content-Type: ' . $request->header('Content-Type'));
            \Log::info('Raw content: ' . $request->getContent());
            
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'content' => 'required|string',
                'category' => 'required|string|max:100',
                'source' => 'required|string|max:100',
                'author' => 'nullable|string|max:100',
                'published_at' => 'nullable|date',
                'read_more_url' => 'nullable|url|max:500'
            ]);

            if ($validator->fails()) {
                \Log::info('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $healthTip = HealthTip::create($validator->validated());

            \Log::info('Health tip created successfully:', ['id' => $healthTip->id]);

            return response()->json([
                'success' => true,
                'data' => new HealthTipResource($healthTip),
                'message' => 'Health tip created successfully'
            ], 201);
            
        } catch (\Exception $e) {
            \Log::error('Error creating health tip: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating health tip: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified health tip.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            \Log::info("Update request for ID: $id", $request->all());
            \Log::info('Request headers:', $request->headers->all());
            \Log::info('Content-Type: ' . $request->header('Content-Type'));
            \Log::info('Raw content: ' . $request->getContent());
            
            $healthTip = HealthTip::find($id);
            
            if (!$healthTip) {
                \Log::info("Health tip not found: $id");
                return response()->json([
                    'success' => false,
                    'message' => 'Health tip not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'content' => 'sometimes|required|string',
                'category' => 'sometimes|required|string|max:100',
                'source' => 'sometimes|required|string|max:100',
                'author' => 'nullable|string|max:100',
                'published_at' => 'nullable|date',
                'read_more_url' => 'nullable|url|max:500'
            ]);

            if ($validator->fails()) {
                \Log::info('Update validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();
            \Log::info('Validated data for update:', $validatedData);
            \Log::info('Before update:', $healthTip->toArray());
            
            $healthTip->update($validatedData);
            
            // Reload the model to get the updated data
            $healthTip->refresh();
            \Log::info('After update:', $healthTip->toArray());

            return response()->json([
                'success' => true,
                'data' => new HealthTipResource($healthTip),
                'message' => 'Health tip updated successfully'
            ], 200);
            
        } catch (\Exception $e) {
            \Log::error('Error updating health tip: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating health tip: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified health tip.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $healthTip = HealthTip::find($id);
            
            if (!$healthTip) {
                return response()->json([
                    'success' => false,
                    'message' => 'Health tip not found'
                ], 404);
            }
            
            $healthTip->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Health tip deleted successfully'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting health tip: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get random health tip.
     */
    public function random(): JsonResponse
    {
        try {
            $healthTip = HealthTip::inRandomOrder()->first();
            
            if (!$healthTip) {
                return response()->json([
                    'success' => false,
                    'message' => 'No health tips available'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => new HealthTipResource($healthTip)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving random health tip: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unique categories.
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = HealthTip::select('category')->distinct()->orderBy('category')->pluck('category');
            
            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving categories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unique sources.
     */
    public function sources(): JsonResponse
    {
        try {
            $sources = HealthTip::select('source')->distinct()->orderBy('source')->pluck('source');
            
            return response()->json([
                'success' => true,
                'data' => $sources
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving sources: ' . $e->getMessage()
            ], 500);
        }
    }
}