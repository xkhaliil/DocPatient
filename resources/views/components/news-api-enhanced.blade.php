<div class="w-full">
    <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-blue-200">
        Latest Healthcare News
    </h3>
    <article id="newsCard" class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition-shadow duration-500">
        <div class="p-6">
            <div id="newsLoading" class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-sm text-gray-500">Loading news...</span>
            </div>
            <div id="newsContent" class="hidden">
                <div class="flex items-center gap-3 mb-4 flex-wrap">
                    <span id="newsSource" class="text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                        Source Name
                    </span>
                    <span id="newsDate" class="text-xs text-gray-500">
                        Jan 25, 2026
                    </span>
                </div>
                <h2 id="newsTitle" class="text-2xl font-bold text-gray-900 mb-4 hover:text-blue-600 cursor-pointer leading-tight">
                    Article Title Goes Here
                </h2>
                <p id="newsDescription" class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                    Article description placeholder
                </p>
                <div class="flex items-center justify-between">
                    <span id="newsAuthor" class="text-sm text-gray-500">
                        By Author Name
                    </span>
                    <a id="newsLink" href="#" target="_blank" 
                       class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2 group">
                        Read more 
                        <span class="group-hover:translate-x-1 transition-transform duration-200">→</span>
                    </a>
                </div>
            </div>
            <div id="newsError" class="hidden">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-red-800">Unable to load health tips</h4>
                            <p class="text-sm text-red-600 mt-1">Showing default content instead</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>

<script>
    // News API Component JavaScript with comprehensive error handling
    class NewsComponent {
        constructor() {
            this.elements = {};
            this.fallbackData = this.getFallbackData();
            this.init();
        }

        init() {
            this.cacheElements();
            this.loadNews();
        }

        cacheElements() {
            this.elements = {
                loading: document.getElementById('newsLoading'),
                content: document.getElementById('newsContent'),
                error: document.getElementById('newsError'),
                title: document.getElementById('newsTitle'),
                description: document.getElementById('newsDescription'),
                source: document.getElementById('newsSource'),
                date: document.getElementById('newsDate'),
                author: document.getElementById('newsAuthor'),
                link: document.getElementById('newsLink')
            };
        }

        async loadNews() {
            try {
                this.showLoading();
                
                // Try multiple endpoints with fallback
                const result = await this.fetchWithFallback();
                
                if (result.success && result.data && result.data.length > 0) {
                    this.displayNews(result.data[0]);
                } else if (result.fallback) {
                    this.displayNews(result.fallback);
                    this.showError();
                } else {
                    this.displayFallbackNews();
                    this.showError();
                }
                
            } catch (error) {
                console.error('News component error:', error);
                this.displayFallbackNews();
                this.showError();
            }
        }

        async fetchWithFallback() {
            const endpoints = [
                '/api/health-tips?limit=1&order_by=published_at&order_direction=desc',
                '/api/health-tips/random'
            ];

            for (const endpoint of endpoints) {
                try {
                    const response = await fetch(endpoint);
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        return data;
                    }
                } catch (error) {
                    console.warn(`Failed to fetch from ${endpoint}:`, error);
                }
            }

            return {
                success: false,
                data: [],
                fallback: this.fallbackData[0]
            };
        }

        displayNews(tip) {
            this.elements.title.textContent = tip.title || 'Health Tip';
            this.elements.description.textContent = tip.description || tip.content || 'No description available';
            this.elements.source.textContent = tip.source || 'Health Tips';
            this.elements.author.textContent = tip.author ? `By ${tip.author}` : '';
            
            // Handle read more link
            if (tip.read_more_url && tip.read_more_url !== '#') {
                this.elements.link.href = tip.read_more_url;
                this.elements.link.target = '_blank';
                this.elements.link.style.display = 'flex';
            } else {
                this.elements.link.style.display = 'none';
            }

            // Handle published date
            if (tip.published_at) {
                try {
                    const date = new Date(tip.published_at);
                    this.elements.date.textContent = date.toLocaleDateString('en-US', { 
                        month: 'short', 
                        day: 'numeric', 
                        year: 'numeric' 
                    });
                } catch (dateError) {
                    this.elements.date.textContent = 'Recently';
                }
            } else {
                this.elements.date.textContent = 'Recently';
            }

            this.showContent();
        }

        displayFallbackNews() {
            const randomFallback = this.fallbackData[Math.floor(Math.random() * this.fallbackData.length)];
            this.displayNews(randomFallback);
        }

        showLoading() {
            this.hideAll();
            this.elements.loading.classList.remove('hidden');
        }

        showContent() {
            this.hideAll();
            this.elements.content.classList.remove('hidden');
        }

        showError() {
            this.elements.error.classList.remove('hidden');
        }

        hideAll() {
            this.elements.loading.classList.add('hidden');
            this.elements.content.classList.add('hidden');
            this.elements.error.classList.add('hidden');
        }

        getFallbackData() {
            return [
                {
                    title: '5 Tips for Better Sleep',
                    description: 'Getting quality sleep is essential for your overall health and well-being. Establish a consistent sleep schedule, create a relaxing bedtime routine, and avoid screens before bed.',
                    content: 'Getting quality sleep is essential for your overall health and well-being. Establish a consistent sleep schedule, create a relaxing bedtime routine, and avoid screens before bed.',
                    source: 'Health Tips',
                    author: 'Dr. Smith',
                    published_at: new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString(),
                    read_more_url: '#'
                },
                {
                    title: 'Importance of Regular Exercise',
                    description: 'Regular physical activity is crucial for maintaining good health. Aim for at least 30 minutes of moderate exercise most days of the week.',
                    content: 'Regular physical activity is crucial for maintaining good health. Aim for at least 30 minutes of moderate exercise most days of the week.',
                    source: 'Health Tips',
                    author: 'Dr. Johnson',
                    published_at: new Date(Date.now() - 48 * 60 * 60 * 1000).toISOString(),
                    read_more_url: '#'
                },
                {
                    title: 'Healthy Eating Habits',
                    description: 'A balanced diet is key to maintaining optimal health. Include plenty of fruits, vegetables, whole grains, and lean proteins in your meals.',
                    content: 'A balanced diet is key to maintaining optimal health. Include plenty of fruits, vegetables, whole grains, and lean proteins in your meals.',
                    source: 'Health Tips',
                    author: 'Dr. Williams',
                    published_at: new Date(Date.now() - 72 * 60 * 60 * 1000).toISOString(),
                    read_more_url: '#'
                }
            ];
        }
    }

    // Initialize the component when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        new NewsComponent();
    });

    // Also initialize immediately if DOM is already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            new NewsComponent();
        });
    } else {
        new NewsComponent();
    }
</script>