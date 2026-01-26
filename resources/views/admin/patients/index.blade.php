<x-app-layout title="Patients">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Patients
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-8">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <h3 class="text-2xl font-semibold text-slate-900">All Patients</h3>

            <a href="{{ route('admin.patients.create') }}"
               class="btn btn-primary focus-ring">
                + Add Patient
            </a>
        </div>

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

                @foreach($patients as $patient)
                    <tr class="hover:bg-slate-50">

                        <td class="px-6 py-4 flex items-center space-x-3">
                            <img
                                src="{{ $patient->getFirstMediaUrl('profile')
                                        ?: 'https://ui-avatars.com/api/?name=' . urlencode($patient->name) }}"
                                class="w-10 h-10 rounded-full border border-slate-200 object-cover"
                            >
                            <a href="{{ route('admin.patients.show', $patient->id) }}"
                               class="font-semibold text-slate-900 hover:text-blue-700 focus-ring">
                                {{ $patient->name }}
                            </a>
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $patient->email }}
                        </td>

                        <td class="px-6 py-4 text-right space-x-4">

                            <a href="{{ route('admin.patients.edit', $patient->id) }}"
                               class="btn btn-ghost focus-ring">
                                Edit
                            </a>

                            <form action="{{ route('admin.patients.destroy', $patient->id) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Delete this patient?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn focus-ring text-red-600 hover:text-red-700">
                                    Delete
                                </button>
                            </form>

                        </td>

                    </tr>
                @endforeach

                </tbody>
            </table>

        </div>

        <div class="mt-6">
            {{ $patients->links() }}
        </div>
    </div>

</x-app-layout>
