<?php

namespace App\Services;

use App\Models\HealthTip;
use App\Http\Resources\HealthTipResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class HealthTipService
{
    /**
     * Get health tips with filtering and error handling
     */
    public function getHealthTips(array $filters = []): array
    {
        try {
            $cacheKey = $this->generateCacheKey($filters);
            
            return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($filters) {
                $query = HealthTip::query();
                
                // Apply filters
                $this->applyFilters($query, $filters);
                
                // Apply ordering
                $this->applyOrdering($query, $filters);
                
                // Apply limit
                $this->applyLimit($query, $filters);
                
                $tips = $query->get();
                
                if ($tips->isEmpty()) {
                    return [
                        'success' => false,
                        'data' => [],
                        'message' => 'No health tips found matching your criteria',
                        'fallback' => $this->getFallbackData()
                    ];
                }
                
                return [
                    'success' => true,
                    'data' => HealthTipResource::collection($tips),
                    'count' => $tips->count(),
                    'message' => 'Health tips retrieved successfully'
                ];
            });
            
        } catch (Exception $e) {
            Log::error('HealthTipService error: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'data' => [],
                'message' => 'Unable to fetch health tips at this time',
                'fallback' => $this->getFallbackData()
            ];
        }
    }
    
    /**
     * Get a single health tip by ID
     */
    public function getHealthTipById(int $id): array
    {
        try {
            $tip = HealthTip::find($id);
            
            if (!$tip) {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => 'Health tip not found'
                ];
            }
            
            return [
                'success' => true,
                'data' => new HealthTipResource($tip),
                'message' => 'Health tip retrieved successfully'
            ];
            
        } catch (Exception $e) {
            Log::error('HealthTipService getById error: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'data' => null,
                'message' => 'Unable to fetch health tip'
            ];
        }
    }
    
    /**
     * Get a random health tip
     */
    public function getRandomHealthTip(): array
    {
        try {
            $tip = HealthTip::inRandomOrder()->first();
            
            if (!$tip) {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => 'No health tips available',
                    'fallback' => $this->getFallbackData()[0] ?? null
                ];
            }
            
            return [
                'success' => true,
                'data' => new HealthTipResource($tip),
                'message' => 'Random health tip retrieved successfully'
            ];
            
        } catch (Exception $e) {
            Log::error('HealthTipService getRandom error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'data' => null,
                'message' => 'Unable to fetch random health tip',
                'fallback' => $this->getFallbackData()[0] ?? null
            ];
        }
    }
    
    /**
     * Apply filters to the query
     */
    private function applyFilters($query, array $filters): void
    {
        // Filter by category
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        
        // Filter by source
        if (!empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }
        
        // Search in title, description, and content
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // Filter by published date range
        if (!empty($filters['published_from'])) {
            $query->where('published_at', '>=', $filters['published_from']);
        }
        
        if (!empty($filters['published_to'])) {
            $query->where('published_at', '<=', $filters['published_to']);
        }
    }
    
    /**
     * Apply ordering to the query
     */
    private function applyOrdering($query, array $filters): void
    {
        $orderBy = $filters['order_by'] ?? 'published_at';
        $orderDirection = $filters['order_direction'] ?? 'desc';
        
        $allowedOrderBy = ['title', 'published_at', 'created_at', 'author', 'source'];
        
        if (in_array($orderBy, $allowedOrderBy)) {
            $query->orderBy($orderBy, $orderDirection);
        }
    }
    
    /**
     * Apply limit to the query
     */
    private function applyLimit($query, array $filters): void
    {
        if (!empty($filters['limit'])) {
            $limit = min((int) $filters['limit'], 100); // Max 100 items
            $query->limit($limit);
        }
    }
    
    /**
     * Generate cache key based on filters
     */
    private function generateCacheKey(array $filters): string
    {
        ksort($filters); // Sort for consistent keys
        return 'health_tips_' . md5(json_encode($filters));
    }
    
    /**
     * Get fallback data for error scenarios
     */
    private function getFallbackData(): array
    {
        return [
            [
                'id' => 0,
                'title' => '5 Tips for Better Sleep',
                'description' => 'Getting quality sleep is essential for your overall health and well-being.',
                'content' => 'Getting quality sleep is essential for your overall health and well-being. Establish a consistent sleep schedule, create a relaxing bedtime routine, and avoid screens before bed.',
                'category' => 'General Health',
                'source' => 'Health Tips',
                'author' => 'Dr. Smith',
                'published_at' => now()->subDays(1)->toISOString(),
                'read_more_url' => '#'
            ],
            [
                'id' => 1,
                'title' => 'Importance of Regular Exercise',
                'description' => 'Regular physical activity is crucial for maintaining good health.',
                'content' => 'Regular physical activity is crucial for maintaining good health. Aim for at least 30 minutes of moderate exercise most days of the week.',
                'category' => 'Fitness',
                'source' => 'Health Tips',
                'author' => 'Dr. Johnson',
                'published_at' => now()->subDays(2)->toISOString(),
                'read_more_url' => '#'
            ],
            [
                'id' => 2,
                'title' => 'Healthy Eating Habits',
                'description' => 'A balanced diet is key to maintaining optimal health.',
                'content' => 'A balanced diet is key to maintaining optimal health. Include plenty of fruits, vegetables, whole grains, and lean proteins in your meals.',
                'category' => 'Nutrition',
                'source' => 'Health Tips',
                'author' => 'Dr. Williams',
                'published_at' => now()->subDays(3)->toISOString(),
                'read_more_url' => '#'
            ]
        ];
    }
}