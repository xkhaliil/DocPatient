<x-app-layout title="Appointments">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            All Appointments
        </h2>
    </x-slot>
    {{-- PAGINATION --}}
    <div class="mt-6">
        {{ $appointments->links() }}
    </div>
    <div class="py-8">
        <div class="table-shell">

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="table-head">
                <tr>
                    <th class="px-6 py-3 text-left">
                        Doctor
                    </th>

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

                @foreach($appointments as $appointment)
                    <tr class="hover:bg-slate-50">

                        {{-- Doctor --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">

                                <img
                                    src="{{ $appointment->cabinet->doctor->getFirstMediaUrl('profile')
                                                ?: 'https://ui-avatars.com/api/?size=128&name=' . urlencode($appointment->cabinet->doctor->name) }}"
                                    class="w-10 h-10 rounded-full object-cover border border-slate-200"
                                >

                                <div>
                                    <a href="/admin/appointments/{{ $appointment->id }}" class="font-semibold text-slate-900 hover:text-blue-700 focus-ring">
                                        Dr. {{ $appointment->cabinet->doctor->name }}
                                    </a>
                                    <p class="text-xs text-slate-500">
                                        {{ $appointment->cabinet->doctor->email }}
                                    </p>
                                </div>

                            </div>
                        </td>

                        {{-- Patient --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">

                                <img
                                    src="{{ $appointment->patient->getFirstMediaUrl('profile')
                                                ?: 'https://ui-avatars.com/api/?size=128&name=' . urlencode($appointment->patient->name) }}"
                                    class="w-10 h-10 rounded-full object-cover border border-slate-200"
                                >

                                <div>
                                    <p class="font-semibold text-slate-900">
                                        {{ $appointment->patient->name }}
                                    </p>
                                    <p class="text-xs text-slate-500">{{ $appointment->patient->email }}</p>
                                </div>

                            </div>
                        </td>

                        {{-- Date & Time --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="font-semibold text-slate-900">
                                {{ \Carbon\Carbon::parse($appointment->datetime)->format('d M Y') }}
                            </p>
                            <p class="text-slate-500 text-sm">
                                {{ \Carbon\Carbon::parse($appointment->datetime)->format('H:i') }}
                            </p>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge
                                    @if($appointment->status === 'scheduled') badge-info
                                    @elseif($appointment->status === 'completed') badge-success
                                    @else badge-danger @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                            <div class="flex items-center justify-end space-x-3">

                                {{-- EDIT --}}
                                <a href="/admin/appointments/{{ $appointment->id }}/edit"
                                   class="btn btn-ghost focus-ring">
                                    Edit
                                </a>

                                {{-- DELETE --}}
                                <form action="/admin/appointments/{{ $appointment->id }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn focus-ring text-red-600 hover:text-red-700">
                                        Delete
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>
                @endforeach

                </tbody>

            </table>

        </div>



    </div>

</x-app-layout>
