<x-site-layout title="Welcome">
    
    {{-- Hero Section with News and Map --}}
    <section class="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid lg:grid-cols-2 gap-8 items-start">
                {{-- News Component --}}
                <livewire:news-api-livewire />

                {{-- Google Maps Component --}}
                <div data-user-location="{{ json_encode($userLocation) }}">
                    <x-google-maps-api />
                </div>
            </div>
        </div>
    </section>

    {{-- Welcome Hero Section --}}
    <section class="bg-gradient-to-br from-slate-50 to-blue-50 py-20">
        <div class="max-w-5xl mx-auto text-center px-6">
            <h1 class="text-5xl md:text-6xl font-bold text-slate-900 mb-6 text-balance">
                Your Health, <span class="text-blue-600">Simplified</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-600 mb-8 text-pretty max-w-3xl mx-auto leading-relaxed">
                Connect with top-rated doctors, explore trusted medical cabinets, and book your appointment in seconds. Healthcare made easy.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/cabinets" class="inline-flex items-center justify-center px-8 py-4 text-base font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200 transform hover:scale-105">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Find a Doctor
                </a>
                <a href="/appointments" class="inline-flex items-center justify-center px-8 py-4 text-base font-medium text-blue-600 bg-white border-2 border-blue-600 rounded-lg hover:bg-blue-50 focus:ring-4 focus:ring-blue-200 transition-all duration-200 transform hover:scale-105">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    View Appointments
                </a>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Easy Booking --}}
                <div class="text-center p-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-slate-900">Easy Booking</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Schedule appointments with just a few clicks. No phone calls needed.
                    </p>
                </div>

                {{-- Top Doctors --}}
                <div class="text-center p-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-slate-900">Top Doctors</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Access to verified, highly-rated medical professionals in your area.
                    </p>
                </div>

                {{-- Find Nearby --}}
                <div class="text-center p-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-slate-900">Find Nearby</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Locate medical centers close to you with our interactive map.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Popular Cabinets Section --}}
    <section class="py-20 bg-gradient-to-br from-slate-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Most Popular Medical Centers
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Discover trusted healthcare providers with excellent patient reviews and high appointment volumes.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($popularCabinets as $cabinet)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        {{-- Doctor Picture --}}
                        <div class="p-6 flex items-center">
                            <img
                                src="{{ $cabinet->doctor->getFirstMediaUrl('profile')
                                ?: 'https://ui-avatars.com/api/?size=128&name=' . urlencode($cabinet->doctor->name) }}"
                                class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-lg"
                                alt="{{ $cabinet->doctor->name }}"
                            >
                            <div class="ml-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600 mb-1">Doctor</p>
                                <p class="text-lg font-semibold text-slate-900">
                                    {{ $cabinet->doctor->name }}
                                </p>
                                <p class="text-sm text-slate-500">{{ $cabinet->doctor->email }}</p>
                            </div>
                        </div>

                        {{-- Cabinet Content --}}
                        <div class="px-6 pb-6">
                            <h3 class="text-xl font-semibold text-slate-900 mb-3">
                                {{ $cabinet->name }}
                            </h3>

                            @if($cabinet->location)
                                <p class="text-slate-600 text-sm mb-4 flex items-start">
                                    <svg class="h-4 w-4 text-slate-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ Str::limit($cabinet->location, 70) }}
                                </p>
                            @endif

                            {{-- Popularity Badge --}}
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ $cabinet->appointments_count }} appointments this month
                                </span>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex justify-between gap-3">
                                <a href="/cabinets/{{ $cabinet->id }}"
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors duration-200">
                                    View Cabinet
                                </a>

                                <a href="/appointments/create?cabinet_id={{ $cabinet->id }}"
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-site-layout>