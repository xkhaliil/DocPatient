<x-app-layout title="Appointment Details">

    <div class="max-w-4xl mx-auto px-6 py-10 space-y-10">

        {{-- APPOINTMENT INFO --}}
        <div class="card p-8">

            <h1 class="text-2xl font-semibold text-slate-900 mb-6">Appointment Details</h1>

            <div class="space-y-4 text-slate-700">

                <p><strong>Date:</strong>
                    {{ \Carbon\Carbon::parse($appointment->datetime)->format('l, d M Y') }}
                </p>

                <p><strong>Time:</strong>
                    {{ \Carbon\Carbon::parse($appointment->datetime)->format('H:i') }}
                </p>

                <p><strong>Status:</strong>
                    <span class="badge
                        @if($appointment->status === 'scheduled') badge-info
                        @elseif($appointment->status === 'completed') badge-success
                        @else badge-danger @endif">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </p>
            </div>

        </div>



        {{-- PATIENT INFO --}}
        <div class="card p-8">

            <h2 class="text-xl font-semibold text-slate-900 mb-6">Patient Information</h2>

            <div class="flex items-center gap-6">

                <img
                    src="{{ $appointment->patient->getFirstMediaUrl('profile')
                        ?: 'https://ui-avatars.com/api/?size=200&name=' . urlencode($appointment->patient->name) }}"
                    class="w-24 h-24 rounded-full border border-slate-200 object-cover shadow"
                >

                <div>
                    <p class="text-2xl font-semibold text-slate-900">
                        {{ $appointment->patient->name }}
                    </p>
                    <p class="text-slate-500">{{ $appointment->patient->email }}</p>
                </div>

            </div>

        </div>



        {{-- ACTIONS --}}
        <div class="card p-8">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Manage Appointment</h3>

            <div class="flex items-center gap-4">

                <a href="{{ route('doctor.appointments.edit', $appointment->id) }}"
                   class="btn btn-primary focus-ring">
                    Edit Status
                </a>

            </div>
        </div>

    </div>

</x-app-layout>
