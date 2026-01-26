<x-app-layout title="Cabinet Details">

    <div class="max-w-6xl mx-auto px-6 py-10 space-y-10">

        {{-- PAGE TITLE --}}
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">
                Cabinet: {{ $cabinet->name }}
            </h1>
            <p class="text-slate-500">Overview of doctor and scheduled appointments</p>
        </div>



        {{-- DOCTOR & CABINET INFO --}}
        <div class="card p-8">

            <div class="flex flex-col md:flex-row md:items-center gap-8">

                {{-- Doctor Photo --}}
                <img
                    src="{{ $cabinet->doctor->getFirstMediaUrl('profile')
                        ?: 'https://ui-avatars.com/api/?size=200&name=' . urlencode($cabinet->doctor->name) }}"
                    class="w-36 h-36 rounded-full object-cover shadow border border-slate-200"
                >

                {{-- Doctor Details --}}
                <div class="flex-1">

                    <h2 class="text-2xl font-semibold text-slate-900">
                        Dr. {{ $cabinet->doctor->name }}
                    </h2>

                    <p class="text-slate-500">{{ $cabinet->doctor->email }}</p>

                    @if($cabinet->doctor->specialty ?? false)
                        <p class="badge badge-info mt-2 inline-flex">
                            🩺 {{ $cabinet->doctor->specialty }}
                        </p>
                    @endif

                    <div class="mt-6 space-y-2 text-slate-700">
                        <p><strong>Cabinet:</strong> {{ $cabinet->name }}</p>
                        <p><strong>Location:</strong> {{ $cabinet->location }}</p>
                        <p><strong>Doctor since:</strong> {{ $cabinet->doctor->created_at->format('d M Y') }}</p>
                    </div>

                </div>

            </div>

        </div>



        {{-- APPOINTMENTS LIST --}}
        <div class="card p-8">

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-slate-900">
                    Appointments
                </h2>

                <span class="text-sm text-slate-500">
                    {{ $cabinet->appointments->count() }} total
                </span>
            </div>

            @if($cabinet->appointments->isEmpty())
                <p class="text-slate-500 italic">No appointments for this cabinet.</p>
            @else

                <div class="divide-y divide-slate-200">

                    @foreach($cabinet->appointments as $appointment)
                        <div class="py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            {{-- LEFT: Patient Info --}}
                            <div class="flex items-center gap-4">

                                {{-- Patient Photo --}}
                                <img
                                    src="{{ $appointment->patient->getFirstMediaUrl('profile')
                                        ?: 'https://ui-avatars.com/api/?size=120&name=' . urlencode($appointment->patient->name) }}"
                                    class="w-12 h-12 rounded-full border border-slate-200 object-cover"
                                >

                                {{-- Patient Name --}}
                                <div>
                                    <p class="font-semibold text-slate-900">
                                        {{ $appointment->patient->name }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        {{ $appointment->patient->email }}
                                    </p>
                                </div>

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

                            {{-- RIGHT: Status + Link --}}
                            <div class="flex flex-col items-start md:items-end gap-2">

                                {{-- Status Badge --}}
                                <span class="badge
                                    @if($appointment->status === 'scheduled') badge-info
                                    @elseif($appointment->status === 'completed') badge-success
                                    @else badge-danger @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>

                                <a href="{{ route('admin.appointments.show', $appointment->id) }}"
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
