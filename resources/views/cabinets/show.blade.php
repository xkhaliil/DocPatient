<x-site-layout title="{{ $cabinet->name }}">

    {{-- TOP BANNER IMAGE --}}
    <div class="w-full h-72 overflow-hidden rounded-2xl shadow-lg">
        <img
            src="{{ $cabinet->doctor->getImageUrl('website') }}"
            class="w-full h-full object-cover"
            alt="Doctor main image"
        >
    </div>

    {{-- PAGE CONTAINER --}}
    <div class="max-w-5xl mx-auto px-6 py-10 space-y-10">

        {{-- TITLE --}}
        <div>
            <h1 class="text-4xl font-semibold text-slate-900">Dr. {{ $cabinet->doctor->name }}</h1>
            <p class="text-lg text-slate-500 mt-2">
                {{ $cabinet->name }}
            </p>
        </div>

        {{-- GRID SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- LEFT: DOCTOR CARD --}}
            <div class="card p-6">
                <div class="flex flex-col items-center text-center">

                    <img
                        src="{{ $cabinet->doctor->getImageUrl('preview')
                            ?: 'https://ui-avatars.com/api/?size=200&name=' . urlencode($cabinet->doctor->name) }}"
                        class="w-32 h-32 rounded-full object-cover border border-slate-200 shadow"
                    >

                    <h2 class="mt-4 text-xl font-semibold text-slate-900">
                        Dr. {{ $cabinet->doctor->name }}
                    </h2>

                    <p class="text-slate-500 text-sm">{{ $cabinet->doctor->email }}</p>

                    @if($cabinet->doctor->specialty ?? false)
                        <p class="badge badge-info mt-2">
                            🩺 {{ $cabinet->doctor->specialty }}
                        </p>
                    @endif

                    <div class="mt-6 text-left space-y-2 w-full">
                        <p class="text-sm text-slate-600"><strong>Experience:</strong> 8+ years</p>
                        <p class="text-sm text-slate-600"><strong>Rating:</strong> ⭐ 4.7 / 5</p>
                        <p class="text-sm text-slate-600"><strong>Languages:</strong> English, French, Arabic</p>
                    </div>
                </div>
            </div>


            {{-- RIGHT: CABINET INFORMATION --}}
            <div class="lg:col-span-2 card p-8">

                <h3 class="text-2xl font-semibold text-slate-900 mb-6">Cabinet Information</h3>

                <div class="space-y-4">
                    <p class="text-slate-700 text-lg">
                        <strong>📍 Location:</strong>
                        <span class="text-slate-500">{{ $cabinet->location }}</span>
                    </p>

                    @if($cabinet->description)
                        <p class="text-slate-700 leading-relaxed">
                            {{ $cabinet->description }}
                        </p>
                    @else
                        <p class="text-slate-500 italic">
                            No description available.
                        </p>
                    @endif

                    {{-- OPTIONAL EXTRA DETAILS --}}
                    <div class="mt-6">
                        <h4 class="text-xl font-semibold text-slate-900 mb-2">Working Hours</h4>
                        <ul class="text-slate-600 space-y-1">
                            <li>🕗 Monday – Friday: 08:00 - 18:00</li>
                            <li>🕤 Saturday: 09:00 - 13:00</li>
                            <li>❌ Sunday: Closed</li>
                        </ul>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-xl font-semibold text-slate-900 mb-2">Contact</h4>
                        <p class="text-slate-600">📧 {{ $cabinet->doctor->email }}</p>
                        <p class="text-slate-600">📞 {{ $cabinet->phone ?? '+000 00 000 000' }}</p>
                    </div>

                    {{-- BOOK APPOINTMENT BUTTON --}}
                    <div class="mt-8">
                        <a href="/appointments/create?cabinet_id={{ $cabinet->id }}"
                           class="btn btn-primary w-full justify-center focus-ring">
                            Book Appointment
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </div>

</x-site-layout>
