    <x-site-layout title="Welcome">

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

