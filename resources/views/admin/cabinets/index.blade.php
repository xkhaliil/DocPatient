<x-app-layout title="Cabinets">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            All Cabinets
        </h2>
    </x-slot>
    {{-- PAGINATION --}}
    <div class="mt-6">
        {{ $cabinets->links() }}
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
                        Cabinet
                    </th>

                    <th class="px-6 py-3 text-left">
                        Location
                    </th>

                    <th class="px-6 py-3 text-right">
                        Actions
                    </th>
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-slate-100 text-sm text-slate-700">
                @foreach($cabinets as $cabinet)
                    <tr class="hover:bg-slate-50">

                        {{-- DOCTOR --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">

                                <img
                                    src="{{ $cabinet->doctor->getFirstMediaUrl('profile')
                                        ?: 'https://ui-avatars.com/api/?size=128&name=' . urlencode($cabinet->doctor->name) }}"
                                    class="w-10 h-10 rounded-full object-cover border border-slate-200"
                                >

                                <div>
                                    <p class="font-semibold text-slate-900">
                                        Dr. {{ $cabinet->doctor->name }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        {{ $cabinet->doctor->email }}
                                    </p>
                                </div>

                            </div>
                        </td>

                        {{-- CABINET NAME --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="/admin/cabinets/{{ $cabinet->id }}"
                               class="text-slate-900 font-semibold hover:text-blue-700 focus-ring">
                                {{ $cabinet->name }}
                            </a>
                        </td>

                        {{-- CABINET LOCATION --}}
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                            {{ Str::limit($cabinet->location, 40) }}
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-4">

                                {{-- EDIT --}}
                                <a href="/admin/cabinets/{{ $cabinet->id }}/edit"
                                   class="btn btn-ghost focus-ring">
                                    Edit
                                </a>

                                {{-- DELETE --}}
                                <form action="/admin/cabinets/{{ $cabinet->id }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure?');">
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
