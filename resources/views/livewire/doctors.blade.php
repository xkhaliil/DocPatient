<div>
    <!-- Search Bar -->
    <div class="mb-6">
        <div class="relative">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search doctors by name or email..."
                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            @if($search)
                <button
                    wire:click="clearSearch"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="mb-4">
        <div class="flex items-center justify-center py-2">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
            <span class="ml-2 text-gray-600">Searching...</span>
        </div>
    </div>

    <!-- Results Count -->
    @if($search)
        <div class="mb-4 text-sm text-gray-600">
            Found {{ $cabinets->total() }} doctor{{ $cabinets->total() !== 1 ? 's' : '' }} matching "{{ $search }}"
        </div>
    @endif

    <div class="mt-6">
        {{ $cabinets->links() }}
    </div>
    
    <!-- Doctors Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        @forelse($cabinets as $cabinet)

            <div class="card p-5 transition hover:-translate-y-0.5 hover:shadow-lg">

                <div class="flex items-center mb-4">
                    <img
                        src="{{ $cabinet->doctor->getFirstMediaUrl('profile')
                              ?: 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($cabinet->doctor->name) }}"
                        class="h-16 w-16 rounded-full object-cover border border-slate-200 shadow-sm"
                        alt="Doctor Photo"
                    >

                    <div class="ml-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Doctor</p>
                        <p class="font-semibold text-slate-900 text-lg">
                            {{ $cabinet->doctor->name }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ $cabinet->doctor->email }}
                        </p>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-900">
                    <a href="/cabinets/{{ $cabinet->id }}" class="focus-ring hover:text-blue-700 transition">
                        {{ $cabinet->name }}
                    </a>
                </h3>

                @if($cabinet->location)
                    <p class="text-sm text-slate-500 mt-2">
                        📍 {{ Str::limit($cabinet->location, 70) }}
                    </p>
                @endif

                <div class="mt-5 pt-4 border-t border-slate-200">
                    <a href="/appointments/create?cabinet_id={{ $cabinet->id }}"
                       class="btn btn-primary w-full justify-center focus-ring">
                        Make Appointment
                    </a>
                </div>

            </div>

        @empty
            <!-- No Results Message -->
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No doctors found</h3>
                <p class="text-gray-500">
                    @if($search)
                        No doctors match your search for "{{ $search }}". Try a different name.
                    @else
                        No doctors are currently available. Please check back later.
                    @endif
                </p>
            </div>
        @endforelse

    </div>
</div>
