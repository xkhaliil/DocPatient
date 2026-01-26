<x-app-layout title="My Patients">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            My Patients
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
                        Email
                    </th>

                    <th class="px-6 py-3 text-right">
                        Actions
                    </th>
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-slate-100 text-sm text-slate-700">
                @forelse($patients as $patient)
                    <tr class="hover:bg-slate-50">

                        {{-- PATIENT --}}
                        <td class="px-6 py-4 flex items-center gap-3">
                            <img
                                src="{{ $patient->getFirstMediaUrl('profile')
                                        ?: 'https://ui-avatars.com/api/?name=' . urlencode($patient->name) }}"
                                class="w-10 h-10 rounded-full border border-slate-200 object-cover"
                            >
                            <span class="font-semibold text-slate-900">
                                    {{ $patient->name }}
                                </span>
                        </td>

                        {{-- EMAIL --}}
                        <td class="px-6 py-4 text-slate-600">
                            {{ $patient->email }}
                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('doctor.patients.show', $patient->id) }}"
                               class="btn btn-ghost focus-ring">
                                View
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center px-6 py-8 text-slate-500">
                            No patients found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>

        <div class="mt-6">
            {{ $patients->links() }}
        </div>

    </div>

</x-app-layout>
