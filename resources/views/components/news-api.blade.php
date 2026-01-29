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
        </div>
    </article>
</div>

<script>
    // News API Component JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        loadNews();
    });

    async function loadNews() {
        const newsLoading = document.getElementById('newsLoading');
        const newsContent = document.getElementById('newsContent');
        const newsTitle = document.getElementById('newsTitle');
        const newsDescription = document.getElementById('newsDescription');
        const newsSource = document.getElementById('newsSource');
        const newsDate = document.getElementById('newsDate');
        const newsAuthor = document.getElementById('newsAuthor');
        const newsLink = document.getElementById('newsLink');

        try {
            // Fetch health tips from our API
            const response = await fetch('/api/health-tips?limit=1');
            const data = await response.json();

            if (data.data && data.data.length > 0) {
                const tip = data.data[0];
                
                // Update the UI with the fetched data
                newsTitle.textContent = tip.title;
                newsDescription.textContent = tip.content;
                newsSource.textContent = tip.source || 'Health Tips';
                newsAuthor.textContent = tip.author ? `By ${tip.author}` : '';
                newsLink.href = tip.read_more_url || '#';
                
                // Format date
                if (tip.published_at) {
                    const date = new Date(tip.published_at);
                    newsDate.textContent = date.toLocaleDateString('en-US', { 
                        month: 'short', 
                        day: 'numeric', 
                        year: 'numeric' 
                    });
                }

                // Show content, hide loading
                newsLoading.classList.add('hidden');
                newsContent.classList.remove('hidden');
            } else {
                // Fallback: Show a default health tip if no data
                showDefaultNews();
            }
        } catch (error) {
            console.error('Error loading news:', error);
            showDefaultNews();
        }
    }

    function showDefaultNews() {
        const newsLoading = document.getElementById('newsLoading');
        const newsContent = document.getElementById('newsContent');
        const newsTitle = document.getElementById('newsTitle');
        const newsDescription = document.getElementById('newsDescription');
        const newsSource = document.getElementById('newsSource');
        const newsAuthor = document.getElementById('newsAuthor');
        const newsLink = document.getElementById('newsLink');

        // Default health tip content
        newsTitle.textContent = '5 Tips for Better Sleep';
        newsDescription.textContent = 'Getting quality sleep is essential for your overall health and well-being. Establish a consistent sleep schedule, create a relaxing bedtime routine, and avoid screens before bed.';
        newsSource.textContent = 'Health Tips';
        newsAuthor.textContent = 'By Dr. Smith';
        newsLink.href = '#';

        // Show content, hide loading
        newsLoading.classList.add('hidden');
        newsContent.classList.remove('hidden');
    }
</script>