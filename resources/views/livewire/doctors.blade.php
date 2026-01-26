<div>
    <div class="mt-6">
        {{ $cabinets->links() }}
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        @foreach($cabinets as $cabinet)

            <div class="card p-5 transition hover:-translate-y-0.5">

                <div class="flex items-center mb-4">
                    <img
                        src="{{ $cabinet->doctor->getFirstMediaUrl('profile')
                              ?: 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($cabinet->doctor->name) }}"
                        class="h-16 w-16 rounded-full object-cover border border-slate-200 shadow-sm"
                        alt="Doctor Photo"
                    >

                    <div class="ml-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Doctor</p>
                        <p class="font-semibold text-slate-900 text-lg">
                            {{ $cabinet->doctor->name }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ $cabinet->doctor->email }}
                        </p>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-900">
                    <a href="/cabinets/{{ $cabinet->id }}" class="focus-ring hover:text-blue-700 transition">
                        {{ $cabinet->name }}
                    </a>
                </h3>

                @if($cabinet->location)
                    <p class="text-sm text-slate-500 mt-2">
                        📍 {{ Str::limit($cabinet->location, 70) }}
                    </p>
                @endif

                <div class="mt-5 pt-4 border-t border-slate-200">
                    <a href="/appointments/create?cabinet_id={{ $cabinet->id }}"
                       class="btn btn-primary w-full justify-center focus-ring">
                        Make Appointment
                    </a>
                </div>

            </div>

        @endforeach

    </div>
</div>
