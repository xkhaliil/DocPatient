<x-app-layout title="Appointment Details">

    <div class="max-w-5xl mx-auto px-6 py-10 space-y-10">

        {{-- PAGE TITLE --}}
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Appointment Details</h1>
            <p class="text-slate-500">Overview of patient & doctor information</p>
        </div>



        {{-- APPOINTMENT INFO CARD --}}
        <div class="card p-8">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">📝 Appointment Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Status --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Status</p>
                    <span class="badge mt-2
                        @if($appointment->status === 'scheduled') badge-info
                        @elseif($appointment->status === 'completed') badge-success
                        @else badge-danger @endif">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>

                {{-- Appointment Date --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Date & Time</p>
                    <p class="text-lg font-semibold text-slate-900 mt-1">
                        {{ \Carbon\Carbon::parse($appointment->datetime)->format('l, d M Y - H:i') }}
                    </p>
                </div>

            </div>
        </div>



        {{-- DOCTOR + PATIENT SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- DOCTOR CARD --}}
            <div class="card p-8">
                <h2 class="text-xl font-semibold text-slate-900 mb-6">👨‍⚕️ Doctor Information</h2>

                <div class="flex items-center gap-6">

                    {{-- Doctor photo --}}
                    <img
                        src="{{ $appointment->cabinet->doctor->getFirstMediaUrl('profile')
                            ?: 'https://ui-avatars.com/api/?size=200&name=' . urlencode($appointment->cabinet->doctor->name) }}"
                        class="w-28 h-28 rounded-full border border-slate-200 shadow object-cover"
                    >

                    <div>
                        <p class="text-2xl font-semibold text-slate-900">
                            Dr. {{ $appointment->cabinet->doctor->name }}
                        </p>
                        <p class="text-slate-500">{{ $appointment->cabinet->doctor->email }}</p>

                        @if($appointment->cabinet->doctor->specialty ?? false)
                            <p class="badge badge-info mt-2 inline-flex">
                                🩺 {{ $appointment->cabinet->doctor->specialty }}
                            </p>
                        @endif
                    </div>

                </div>

                <div class="mt-6 space-y-2 text-slate-600">
                    <p><strong>Cabinet:</strong> {{ $appointment->cabinet->name }}</p>
                    <p><strong>Location:</strong> {{ $appointment->cabinet->location }}</p>
                </div>
            </div>



            {{-- PATIENT CARD --}}
            <div class="card p-8">
                <h2 class="text-xl font-semibold text-slate-900 mb-6">🧑 Patient Information</h2>

                <div class="flex items-center gap-6">

                    {{-- Patient photo --}}
                    <img
                        src="{{ $appointment->patient->getFirstMediaUrl('profile')
                            ?: 'https://ui-avatars.com/api/?size=200&name=' . urlencode($appointment->patient->name) }}"
                        class="w-28 h-28 rounded-full border border-slate-200 shadow object-cover"
                    >

                    <div>
                        <p class="text-2xl font-semibold text-slate-900">
                            {{ $appointment->patient->name }}
                        </p>
                        <p class="text-slate-500">{{ $appointment->patient->email }}</p>
                    </div>

                </div>

                <div class="mt-6 space-y-2 text-slate-600">
                    <p><strong>Registered:</strong> {{ $appointment->patient->created_at->format('d M Y') }}</p>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
