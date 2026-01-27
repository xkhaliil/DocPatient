<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewsApiService
{
    private string $cacheKey = 'newsdatahub_health_articles';

    // Topics to exclude
    private array $excludedTopics = [
        'politics', 'technology', 'business', 'finance',
        'sports', 'government', 'environment', 'crime'
    ];

    private string $topic = 'health';

    public function getArticles(): array
    {
        // Cache the fetched articles for 30 minutes
        return Cache::remember($this->cacheKey, now()->addMinutes(30), function () {

            $response = Http::withHeaders([
                'X-Api-Key' => config('services.newsdatahub.key'),
                'Accept' => 'application/json',
            ])->get(config('services.newsdatahub.base_url') . '/news', [
                'language' => 'en',
                'topic' => $this->topic,
                'exclude_topic' => implode(',', $this->excludedTopics),
                'per_page' => 10,
            ]);

            if (!$response->successful()) {
                logger()->error('NewsDataHub API failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            }

            return $response->json()['data'] ?? [];
        });
    }

    public function getRandomArticle(): ?array
    {
        $articles = $this->getArticles();

        if (empty($articles)) {
            return null;
        }

        return $articles[array_rand($articles)];
    }
}
