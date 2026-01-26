<x-app-layout title="Patient Details">

    <div class="max-w-5xl mx-auto px-6 py-10 space-y-10">

        {{-- PATIENT CARD --}}
        <div class="card p-8">

            <div class="flex items-center gap-6 mb-6">

                <img
                    src="{{ $patient->getFirstMediaUrl('profile')
                        ?: 'https://ui-avatars.com/api/?size=200&name=' . urlencode($patient->name) }}"
                    class="w-28 h-28 rounded-full border border-slate-200 object-cover shadow"
                >

                <div>
                    <h1 class="text-3xl font-semibold text-slate-900">
                        {{ $patient->name }}
                    </h1>

                    <p class="text-slate-500">{{ $patient->email }}</p>

                    <p class="text-sm text-slate-500 mt-2">
                        Registered: {{ $patient->created_at->format('d M Y') }}
                    </p>
                </div>

            </div>
        </div>




        {{-- APPOINTMENTS WITH THIS DOCTOR --}}
        <div class="card p-8">

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-slate-900">
                    Appointments With You
                </h2>

                <span class="text-sm text-slate-500">
                    {{ $appointments->count() }} total
                </span>
            </div>

            @if($appointments->isEmpty())
                <p class="text-slate-500 italic">No appointments with this patient.</p>
            @else
                <div class="divide-y divide-slate-200">

                    @foreach($appointments as $appointment)
                        <div class="py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            {{-- LEFT: Cabinet --}}
                            <div>
                                <p class="font-semibold text-slate-900">
                                    {{ $appointment->cabinet->name }}
                                </p>
                                @if($appointment->cabinet->location)
                                    <p class="text-xs text-slate-500">
                                        📍 {{ Str::limit($appointment->cabinet->location, 50) }}
                                    </p>
                                @endif
                            </div>

                            {{-- MIDDLE: Date --}}
                            <div class="text-sm text-slate-700">
                                <p class="font-semibold text-slate-900">
                                    {{ \Carbon\Carbon::parse($appointment->datetime)->format('d M Y') }}
                                </p>
                                <p class="text-slate-500">
                                    {{ \Carbon\Carbon::parse($appointment->datetime)->format('H:i') }}
                                </p>
                            </div>

                            {{-- RIGHT: Status --}}
                            <div class="flex flex-col md:items-end gap-2">

                                <span class="badge
                                    @if($appointment->status === 'scheduled') badge-info
                                    @elseif($appointment->status === 'completed') badge-success
                                    @else badge-danger @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>

                                <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                   class="btn btn-ghost focus-ring">
                                    View Appointment
                                </a>
                            </div>

                        </div>
                    @endforeach

                </div>
            @endif

        </div>

    </div>

</x-app-layout>
