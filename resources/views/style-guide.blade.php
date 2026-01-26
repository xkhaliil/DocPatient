<x-app-layout title="Style Guide">
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-semibold text-slate-900">Healthcare UI Style Guide</h2>
            <p class="text-sm text-slate-500">Consistent visual language for clean, trusted clinical experiences.</p>
        </div>
    </x-slot>

    <div class="space-y-10">
        <section class="card p-6">
            <h3 class="text-lg font-semibold text-slate-900">Color Palette</h3>
            <p class="text-sm text-slate-500 mt-1">Primary blues for trust, soft neutrals for clarity, teal accent for care.</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="rounded-xl border border-slate-200 overflow-hidden">
                    <div class="h-20" style="background: var(--brand-600)"></div>
                    <div class="p-3 text-sm font-medium text-slate-700">Brand 600</div>
                </div>
                <div class="rounded-xl border border-slate-200 overflow-hidden">
                    <div class="h-20" style="background: var(--brand-500)"></div>
                    <div class="p-3 text-sm font-medium text-slate-700">Brand 500</div>
                </div>
                <div class="rounded-xl border border-slate-200 overflow-hidden">
                    <div class="h-20" style="background: var(--accent-500)"></div>
                    <div class="p-3 text-sm font-medium text-slate-700">Accent</div>
                </div>
                <div class="rounded-xl border border-slate-200 overflow-hidden">
                    <div class="h-20" style="background: var(--surface-50)"></div>
                    <div class="p-3 text-sm font-medium text-slate-700">Surface</div>
                </div>
            </div>
        </section>

        <section class="card p-6">
            <h3 class="text-lg font-semibold text-slate-900">Typography</h3>
            <div class="space-y-3 mt-4">
                <div class="text-3xl font-semibold text-slate-900">Clinical Dashboard Heading</div>
                <div class="text-xl font-semibold text-slate-800">Section Heading</div>
                <div class="text-base text-slate-700">Primary body text for instructions, patient context, and guidance.</div>
                <div class="text-sm text-slate-500">Secondary text for metadata, timestamps, and helper notes.</div>
            </div>
        </section>

        <section class="card p-6">
            <h3 class="text-lg font-semibold text-slate-900">Buttons</h3>
            <div class="flex flex-wrap gap-3 mt-4">
                <button class="btn btn-primary focus-ring">Primary Action</button>
                <button class="btn btn-secondary focus-ring">Secondary Action</button>
                <button class="btn btn-ghost focus-ring">Tertiary Action</button>
                <button class="btn focus-ring bg-red-600 text-white hover:bg-red-700">Critical Action</button>
            </div>
        </section>

        <section class="card p-6">
            <h3 class="text-lg font-semibold text-slate-900">Badges</h3>
            <div class="flex flex-wrap gap-3 mt-4">
                <span class="badge badge-info">Scheduled</span>
                <span class="badge badge-success">Completed</span>
                <span class="badge badge-warning">Pending</span>
                <span class="badge badge-danger">Cancelled</span>
            </div>
        </section>

        <section class="card p-6">
            <h3 class="text-lg font-semibold text-slate-900">Form Elements</h3>
            <div class="grid md:grid-cols-2 gap-6 mt-4">
                <div>
                    <x-breeze.input-label value="Patient Name" />
                    <x-breeze.text-input class="mt-2" placeholder="Jane Doe" />
                </div>
                <div>
                    <x-breeze.input-label value="Email Address" />
                    <x-breeze.text-input class="mt-2" placeholder="patient@email.com" />
                </div>
                <div>
                    <x-breeze.input-label value="Appointment Reason" />
                    <textarea class="input mt-2" rows="3" placeholder="Describe the reason for the visit"></textarea>
                </div>
                <div class="flex items-end">
                    <button class="btn btn-primary focus-ring w-full">Save Details</button>
                </div>
            </div>
        </section>

        <section class="card p-6">
            <h3 class="text-lg font-semibold text-slate-900">Cards & Layout</h3>
            <div class="grid md:grid-cols-2 gap-6 mt-4">
                <div class="card p-4">
                    <div class="text-sm text-slate-500">Upcoming Appointment</div>
                    <div class="text-lg font-semibold text-slate-900 mt-2">Dr. Amira Khan</div>
                    <div class="text-sm text-slate-500">Mon · 10:00 AM · Cardiology</div>
                </div>
                <div class="card-soft p-4">
                    <div class="text-sm text-slate-500">Care Tip</div>
                    <div class="text-base text-slate-700 mt-2">Ensure patient vitals are reviewed before consultation.</div>
                </div>
            </div>
        </section>

        <section class="card p-6">
            <h3 class="text-lg font-semibold text-slate-900">Tables</h3>
            <div class="table-shell mt-4">
                <table class="min-w-full">
                    <thead class="table-head">
                    <tr>
                        <th class="px-6 py-3 text-left">Patient</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">Laila Noor</td>
                        <td class="px-6 py-4">Aug 22 · 09:30</td>
                        <td class="px-6 py-4"><span class="badge badge-info">Scheduled</span></td>
                        <td class="px-6 py-4 text-right"><button class="btn btn-ghost focus-ring">Review</button></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">Samir Blake</td>
                        <td class="px-6 py-4">Aug 22 · 13:00</td>
                        <td class="px-6 py-4"><span class="badge badge-success">Completed</span></td>
                        <td class="px-6 py-4 text-right"><button class="btn btn-ghost focus-ring">Summary</button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card p-6">
            <h3 class="text-lg font-semibold text-slate-900">Iconography</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-4 text-sm text-slate-600">
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2c-4.4 0-8 3.6-8 8 0 6 8 12 8 12s8-6 8-12c0-4.4-3.6-8-8-8zm0 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                    </svg>
                    Location
                </div>
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 2h12a2 2 0 0 1 2 2v16l-8-4-8 4V4a2 2 0 0 1 2-2z"/>
                    </svg>
                    Records
                </div>
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3H8l-5 3V6z"/>
                    </svg>
                    Messaging
                </div>
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 1a5 5 0 0 1 5 5v2h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2V6a5 5 0 0 1 5-5zm-3 7h6V6a3 3 0 1 0-6 0v2z"/>
                    </svg>
                    Security
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
