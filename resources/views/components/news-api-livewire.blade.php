<?php

use Livewire\Component;
use App\Services\HealthTipService;

new class extends Component {
    public $title = '';
    public $description = '';
    public $source = 'Health Tips';
    public $author = '';
    public $readMoreUrl = '#';
    public $publishedAt = '';
    public $isLoading = true;
    public $hasError = false;
    public $errorMessage = '';

    public function mount(HealthTipService $healthTipService)
    {
        $this->loadNews($healthTipService);
    }

    public function loadNews(HealthTipService $healthTipService)
    {
        $this->isLoading = true;
        $this->hasError = false;
        
        try {
            // Try latest news first
            $result = $healthTipService->getHealthTips([
                'limit' => 1,
                'order_by' => 'published_at',
                'order_direction' => 'desc'
            ]);
            
            // If no latest news, try random
            if (!$result['success'] && isset($result['fallback'])) {
                $result = $healthTipService->getRandomHealthTip();
            }
            
            // Display the result
            if ($result['success'] && !empty($result['data'])) {
                $this->displayHealthTip($result['data'][0] ?? $result['data']);
            } elseif (isset($result['fallback'])) {
                // Use fallback data (this is valid, not an error)
                $this->displayHealthTip($result['fallback']);
            } else {
                // Complete failure
                $this->showError('Unable to load health tips', 'Showing default content instead');
            }
        } catch (\Exception $e) {
            \Log::error('News Livewire component error: ' . $e->getMessage());
            $this->showError('Unable to load health tips', 'Showing default content instead');
        } finally {
            $this->isLoading = false;
        }
    }

    private function displayHealthTip($tip)
    {
        $this->title = $tip['title'] ?? 'Health Tip';
        $this->description = $tip['description'] ?? $tip['content'] ?? 'No description available';
        $this->source = $tip['source'] ?? 'Health Tips';
        $this->author = $tip['author'] ?? '';
        $this->readMoreUrl = $tip['read_more_url'] ?? '#';
        $this->publishedAt = $tip['published_at'] ?? now()->format('M d, Y');
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
                                <h4 class="text-sm font-medium text-red-800">Unable to load health tips</h4>
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
                    
                    <div class="flex items-center justify-between">
                        @if($author)
                            <span class="text-sm text-gray-500">
                                By {{ $author }}
                            </span>
                        @else
                            <span></span>
                        @endif
                        
                        @if($readMoreUrl && $readMoreUrl !== '#')
                            <a href="{{ $readMoreUrl }}" target="_blank" 
                               class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2 group">
                                Read more 
                                <span class="group-hover:translate-x-1 transition-transform duration-200">→</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </article>
</div>