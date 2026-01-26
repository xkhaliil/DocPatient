<x-app-layout title="My Appointments">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            My Appointments
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="table-shell">

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="table-head">
                <tr>
                    <th class="px-6 py-3 text-left">
                        Patient
                    </th>

                    <th class="px-6 py-3 text-left">
                        Date & Time
                    </th>

                    <th class="px-6 py-3 text-left">
                        Status
                    </th>

                    <th class="px-6 py-3 text-right">
                        Actions
                    </th>
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-slate-100 text-sm text-slate-700">
                @forelse($appointments as $appointment)
                    <tr class="hover:bg-slate-50">

                        {{-- PATIENT --}}
                        <td class="px-6 py-4 whitespace-nowrap flex items-center gap-3">
                            <img
                                src="{{ $appointment->patient->getFirstMediaUrl('profile')
                                    ?: 'https://ui-avatars.com/api/?name=' . urlencode($appointment->patient->name) }}"
                                class="w-10 h-10 rounded-full object-cover border border-slate-200"
                            >
                            <div>
                                <p class="font-semibold text-slate-900">
                                    {{ $appointment->patient->name }}
                                </p>
                                <p class="text-xs text-slate-500">{{ $appointment->patient->email }}</p>
                            </div>
                        </td>

                        {{-- DATE --}}
                        <td class="px-6 py-4 whitespace-nowrap text-slate-700">
                            <p class="font-semibold text-slate-900">
                                {{ \Carbon\Carbon::parse($appointment->datetime)->format('d M Y') }}
                            </p>
                            <p class="text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($appointment->datetime)->format('H:i') }}
                            </p>
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="badge
                                @if($appointment->status === 'scheduled') badge-info
                                @elseif($appointment->status === 'completed') badge-success
                                @else badge-danger @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                               class="btn btn-ghost focus-ring">
                                View
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                            No appointments found.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-6">
            {{ $appointments->links() }}
        </div>

    </div>

</x-app-layout>
