<x-site-layout>


        <h1 class="text-2xl font-semibold text-slate-900 mb-6 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2
                         2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Appointment Details
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- STATUS --}}
            <div class="card-soft p-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Status</p>
                <p class="text-lg font-semibold mt-1">
                    <span class="badge
                        {{ $appointment->status === 'scheduled' ? 'badge-info' : '' }}
                        {{ $appointment->status === 'completed' ? 'badge-success' : '' }}
                        {{ $appointment->status === 'cancelled' ? 'badge-danger' : '' }}
                    ">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </p>
            </div>

            {{-- DATE & TIME --}}
            <div class="card-soft p-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Appointment Date</p>
                <p class="text-lg font-semibold mt-1 text-slate-800">
                    {{ \Carbon\Carbon::parse($appointment->datetime)->format('l, d M Y - H:i') }}
                </p>
            </div>

        </div>

        <hr class="my-8 border-slate-200">

        {{-- PATIENT INFO --}}
        <h2 class="text-xl font-semibold text-slate-800 mb-4">Patient Information</h2>

        <div class="card-soft p-4 mb-6">
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Name</p>
            <p class="text-lg font-semibold text-slate-800">{{ $appointment->patient->name }}</p>

            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mt-3">Email</p>
            <p class="text-md text-slate-700">{{ $appointment->patient->email }}</p>
        </div>

        {{-- DOCTOR INFO --}}
        <h2 class="text-xl font-semibold text-slate-800 mb-4">Doctor Information</h2>

        <div class="card-soft p-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Doctor Name</p>
            <p class="text-lg font-semibold text-slate-800">{{ $appointment->cabinet->doctor->name }}</p>

            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mt-3">Doctor Email</p>
            <p class="text-md text-slate-700">{{ $appointment->cabinet->doctor->email }}</p>

            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mt-3">Cabinet</p>
            <p class="text-md text-slate-700">{{ $appointment->cabinet->name }}</p>
        </div>

        {{-- RETURN BUTTON --}}
        <div class="mt-10 text-center">
            <a href="{{ url()->previous() }}"
               class="btn btn-secondary focus-ring">
                Go Back
            </a>
        </div>



</x-site-layout>
