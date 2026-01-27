    <x-site-layout title="Welcome">
        
        <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-2 mb-10 rounded-2xl">
            <div class="flex items-center justify-center space-x-10">
                <div class="">
            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
             <div class="md:flex">
                
                <div class="p-6 md:w-3/5 lg:w-full">
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded">Source Name</span>
                        <span class="text-xs text-gray-500">Jan 25, 2026</span>
                        <span class="text-xs text-gray-500">• Reliability: 8/10</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3 hover:text-blue-600 cursor-pointer">
                        Article Title Goes Here - This is a Placeholder
                    </h2>
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        This is the article description or excerpt. It provides a brief summary of the article content to give readers an idea of what the article is about before they click through to read more.
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">By Author Name</span>
                        <a href="#" 
                           class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                            Read more 
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </article>
                </div>
                <div class="bg-white rounded-lg p-8 max-w-md w-full text-black">
el trya
                </div>

            </div>
        </section>
        {{-- HERO SECTION --}}
        <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20 mb-10 rounded-2xl">
            <div class="max-w-5xl mx-auto text-center px-6">
                <h1 class="text-4xl md:text-5xl font-semibold mb-4">
                    Welcome to Your Medical Appointment System
                </h1>
                <p class="text-lg md:text-xl text-blue-100">
                    Find top-rated doctors, explore trusted medical cabinets, and book your appointment in seconds.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="/cabinets" class="btn btn-primary focus-ring">Find a Doctor</a>
                    <a href="/appointments" class="btn btn-secondary focus-ring">View Appointments</a>
                </div>
            </div>
        </section>

        {{-- POPULAR CABINETS SECTION --}}
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-2xl font-semibold text-slate-900 mb-6 text-center">
                ⭐ Most Popular Cabinets
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

                @foreach($popularCabinets as $cabinet)
                    <div class="card overflow-hidden hover:-translate-y-0.5 transition">

                        {{-- Doctor Picture --}}
                        <div class="p-5 flex items-center">
                            <img
                                src="{{ $cabinet->doctor->getFirstMediaUrl('profile')
                                ?: 'https://ui-avatars.com/api/?size=128&name=' . urlencode($cabinet->doctor->name) }}"
                                class="h-20 w-20 rounded-full object-cover border border-slate-200 shadow-sm"
                            >

                            <div class="ml-4">
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Doctor</p>
                                <p class="text-lg font-semibold text-slate-900">
                                    {{ $cabinet->doctor->name }}
                                </p>
                                <p class="text-sm text-slate-500">{{ $cabinet->doctor->email }}</p>
                            </div>
                        </div>

                        {{-- Cabinet Content --}}
                        <div class="px-5 pb-5">
                            <h3 class="text-xl font-semibold text-slate-900 mb-2">
                                {{ $cabinet->name }}
                            </h3>

                            @if($cabinet->location)
                                <p class="text-slate-500 text-sm mb-4">
                                    📍 {{ Str::limit($cabinet->location, 70) }}
                                </p>
                            @endif

                            {{-- Popularity Badge --}}
                            <span class="badge badge-warning mb-4">
                                {{ $cabinet->appointments_count }} appointments this month
                            </span>

                            {{-- Buttons --}}
                            <div class="flex justify-between mt-4">
                                <a href="/cabinets/{{ $cabinet->id }}"
                                   class="btn btn-ghost focus-ring">
                                    View Cabinet
                                </a>

                                <a href="/appointments/create?cabinet_id={{ $cabinet->id }}"
                                   class="btn btn-primary focus-ring">
                                    Book Now
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>

    </x-site-layout>

