<?php

use Livewire\Component;
use App\Services\NewsApiService;
use Carbon\Carbon;

new class extends Component {
    public $title = '';
    public $description = '';
    public $source = 'Healthcare News';
    public $author = '';
    public $readMoreUrl = '#';
    public $publishedAt = '';
    public $isLoading = true;
    public $hasError = false;
    public $errorMessage = '';
    public $isFallback = false;
    public $lastRotationTime = null;
    private $rotationInterval = 30; // minutes

    public function mount(NewsApiService $newsApiService)
    {
        // Check if we need to rotate fallback data on initial load
        $this->checkRotationAndLoad($newsApiService);
    }

    public function loadNews(NewsApiService $newsApiService)
    {
        $this->isLoading = true;
        $this->hasError = false;
        $this->isFallback = false;
        
        try {
            // Try to get articles from News API
            $articles = $newsApiService->getArticles();
            
            if (!empty($articles)) {
                // Get the latest article (first one from the API)
                $article = $articles[0];
                $this->displayArticle($article);
            } else {
                // Try to get a random article as fallback
                $randomArticle = $newsApiService->getRandomArticle();
                if ($randomArticle) {
                    $this->isFallback = true;
                    $this->lastRotationTime = now();
                    $this->displayArticle($randomArticle);
                } else {
                    // Complete failure
                    $this->showError('Unable to load news articles', 'Showing default content instead');
                }
            }
        } catch (\Exception $e) {
            \Log::error('News Livewire component error: ' . $e->getMessage());
            $this->showError('Unable to load news articles', 'Showing default content instead');
        } finally {
            $this->isLoading = false;
        }
    }

    private function checkRotationAndLoad(NewsApiService $newsApiService)
    {
        // Check if we need to rotate fallback data on initial load
        if ($this->shouldRotateFallback()) {
            // Force a fresh load to get new random data
            $this->loadNews($newsApiService);
        } else {
            // Normal load
            $this->loadNews($newsApiService);
        }
    }

    private function shouldRotateFallback(): bool
    {
        // If we don't have a rotation time, we should load fresh
        if (!$this->lastRotationTime) {
            return true;
        }

        // Check if 30 minutes have passed since last rotation
        $rotationTime = Carbon::parse($this->lastRotationTime);
        return now()->diffInMinutes($rotationTime) >= $this->rotationInterval;
    }

    private function displayArticle($article)
    {
        $this->title = $article['title'] ?? 'Healthcare News';
        $this->description = $article['description'] ?? $article['content'] ?? 'No description available';
        
        // Handle source field (can be string or array)
        if (isset($article['source_title'])) {
            $this->source = $article['source_title'];
        } elseif (isset($article['source'])) {
            if (is_array($article['source'])) {
                $this->source = $article['source']['id'] ?? 'Healthcare News';
            } else {
                $this->source = $article['source'];
            }
        } else {
            $this->source = 'Healthcare News';
        }
        
        // Handle creator field (can be string or array)
        if (isset($article['creator'])) {
            if (is_array($article['creator'])) {
                $this->author = implode(', ', $article['creator']);
            } else {
                $this->author = $article['creator'];
            }
        } else {
            $this->author = '';
        }
        
        $this->readMoreUrl = $article['article_link'] ?? ($article['link'] ?? '#');
        $this->publishedAt = isset($article['pub_date']) ? Carbon::parse($article['pub_date'])->format('M d, Y') : (isset($article['pubDate']) ? Carbon::parse($article['pubDate'])->format('M d, Y') : now()->format('M d, Y'));
        $this->hasError = false;
    }

    private function showError(string $title, string $message)
    {
        $this->hasError = true;
        $this->errorMessage = $message;
    }
}; ?>

<div class="w-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-blue-200">
            Latest Healthcare News
        </h3>
        <button wire:click="loadNews" 
                class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                :disabled="$wire.isLoading">
            <span wire:loading.remove>Refresh</span>
            <span wire:loading>Loading...</span>
        </button>
    </div>
    
    @if($isFallback)
        <!-- Fallback Indicator -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-amber-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-amber-800">Showing Sample News Articles</h4>
                    <p class="text-xs text-amber-600 mt-1">This is fallback content that rotates every 30 minutes</p>
                </div>
            </div>
        </div>
    @endif
    
    <article class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition-shadow duration-500">
        <div class="p-6">
            <!-- Loading State -->
            <div wire:loading class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-sm text-gray-500">Loading news...</span>
            </div>

            <!-- Content State -->
            <div wire:loading.remove>
                @if($hasError)
                    <!-- Error State -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-red-800">Unable to load news articles</h4>
                                <p class="text-sm text-red-600 mt-1">{{ $errorMessage }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Success State -->
                    <div class="flex items-center gap-3 mb-4 flex-wrap">
                        <span class="text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                            {{ $source }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $publishedAt }}
                        </span>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 hover:text-blue-600 cursor-pointer leading-tight">
                        {{ $title }}
                    </h2>
                    
                    <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                        {{ $description }}
                    </p>
                    
                    @if($readMoreUrl && $readMoreUrl !== '#')
                        <div class="mb-4">
                            <a href="{{ $readMoreUrl }}" target="_blank" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md group">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Read this article
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-between text-sm">
                        @if($author)
                            <span class="text-gray-500">
                                By {{ $author }}
                            </span>
                        @else
                            <span></span>
                        @endif
                        
                        @if($readMoreUrl && $readMoreUrl !== '#')
                            <a href="{{ $readMoreUrl }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 group">
                                View original
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </article>
</div>