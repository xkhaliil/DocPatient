<x-app-layout title="Patient Details">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            Patient Details
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-6 py-10 space-y-8">

        {{-- PATIENT CARD --}}
        <div class="card p-8">

            <div class="flex items-center space-x-6 mb-6">

                <img
                    src="{{ $patient->getFirstMediaUrl('profile')
                        ?: 'https://ui-avatars.com/api/?size=200&name=' . urlencode($patient->name) }}"
                    class="w-28 h-28 rounded-full border border-slate-200 object-cover shadow"
                >

                <div>
                    <h3 class="text-3xl font-semibold text-slate-900">
                        {{ $patient->name }}
                    </h3>
                    <p class="text-slate-500">{{ $patient->email }}</p>
                </div>

            </div>

            <div class="border-t border-slate-200 pt-6 space-y-2 text-slate-700">
                <p><strong>Role:</strong> Patient</p>
                <p><strong>Registered:</strong> {{ $patient->created_at->format('d M Y') }}</p>
            </div>

        </div>

        {{-- PATIENT APPOINTMENTS --}}
        <div class="card p-8">

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-semibold text-slate-900">
                    Appointments
                </h3>
                {{-- optional: count --}}
                <span class="text-sm text-slate-500">
                    {{ $patient->appointments->count() }} total
                </span>
            </div>

            @if($patient->appointments->isEmpty())
                <p class="text-slate-500 italic">This patient has no appointments yet.</p>
            @else
                <div class="divide-y divide-slate-200">

                    @foreach($patient->appointments as $appointment)
                        <div class="py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            {{-- LEFT: Doctor & Cabinet --}}
                            <div class="flex items-center gap-4">
                                <img
                                    src="{{ $appointment->cabinet->doctor->getFirstMediaUrl('profile')
                                        ?: 'https://ui-avatars.com/api/?size=120&name=' . urlencode($appointment->cabinet->doctor->name) }}"
                                    class="w-12 h-12 rounded-full object-cover border border-slate-200"
                                >

                                <div>
                                    <p class="font-semibold text-slate-900">
                                        Dr. {{ $appointment->cabinet->doctor->name }}
                                    </p>
                                    <p class="text-sm text-slate-600">
                                        {{ $appointment->cabinet->name }}
                                    </p>
                                    @if($appointment->cabinet->location)
                                        <p class="text-xs text-slate-500">
                                            📍 {{ Str::limit($appointment->cabinet->location, 50) }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- MIDDLE: Date & Time --}}
                            <div class="text-sm text-slate-700">
                                <p class="font-semibold text-slate-900">
                                    {{ \Carbon\Carbon::parse($appointment->datetime)->format('d M Y') }}
                                </p>
                                <p class="text-slate-500">
                                    {{ \Carbon\Carbon::parse($appointment->datetime)->format('H:i') }}
                                </p>
                            </div>

                            {{-- RIGHT: Status + Link --}}
                            <div class="flex flex-col items-start md:items-end gap-2">

                                <span class="badge
                                    @if($appointment->status === 'scheduled') badge-info
                                    @elseif($appointment->status === 'completed') badge-success
                                    @else badge-danger @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>

                                <a href="{{ route('admin.appointments.show', $appointment->id ?? $appointment->id) }}"
                                   class="btn btn-ghost focus-ring">
                                    View appointment
                                </a>
                            </div>

                        </div>
                    @endforeach

                </div>
            @endif

        </div>

    </div>

</x-app-layout>
