<x-site-layout title="Appointments">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            All Appointments
        </h2>
    </x-slot>
    {{ $appointments->links() }}

    <div class="max-w-5xl mx-auto mt-8">
        @foreach($appointments as $appointment)
            <a href="/appointments/{{ $appointment->id }}"
               class="block card p-5 mb-4 hover:-translate-y-0.5 transition focus-ring">

                <div class="flex items-center justify-between">

                    <!-- LEFT SIDE: Doctor & Patient -->
                    <div class="flex items-center space-x-6">

                        <!-- DOCTOR PICTURE -->
                        <img
                            src="{{ $appointment->cabinet->doctor->getImageUrl('preview') ?: 'https://ui-avatars.com/api/?name=' . urlencode($appointment->cabinet->doctor->name) }}"
                            class="h-14 w-14 rounded-full object-cover border border-slate-200"
                        >

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-1">Doctor</p>
                            <p class="text-lg font-semibold text-slate-900">
                                {{ $appointment->cabinet->doctor->name }}
                            </p>
                            <p class="text-sm text-slate-500">{{ $appointment->cabinet->doctor->email }}</p>
                        </div>

                    </div>

                    <!-- MIDDLE: PATIENT -->
                    <div class="flex items-center space-x-4">

                        <!-- PATIENT PICTURE -->
                        <img
                            src="{{ $appointment->patient->getFirstMediaUrl('profile') ?: 'https://ui-avatars.com/api/?name=' . urlencode($appointment->patient->name) }}"
                            class="h-14 w-14 rounded-full object-cover border border-slate-200"
                        >

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-1">Patient</p>
                            <p class="text-lg font-semibold text-slate-900">
                                {{ $appointment->patient->name }}
                            </p>
                            <p class="text-sm text-slate-500">{{ $appointment->patient->email }}</p>
                        </div>

                    </div>

                    <!-- RIGHT SIDE: DATE -->
                    <div class="text-right">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Appointment</p>
                        <p class="font-semibold text-slate-900">
                            {{ \Carbon\Carbon::parse($appointment->datetime)->format('d M Y') }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ \Carbon\Carbon::parse($appointment->datetime)->format('H:i') }}
                        </p>

                        <span class="badge mt-2
                            {{ $appointment->status === 'scheduled' ? 'badge-info' : '' }}
                            {{ $appointment->status === 'completed' ? 'badge-success' : '' }}
                            {{ $appointment->status === 'canceled' ? 'badge-danger' : '' }}
                        ">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                </div>

            </a>
        @endforeach

    </div>

</x-site-layout>
