<nav class="nav-shell">
    <div class="container-app nav-inner">
        <div class="nav-left">
            <div class="nav-logo">
                <a href="{{ route('dashboard') }}" class="nav-logo-link">
                    <x-breeze.application-logo class="block h-9 w-auto fill-current text-blue-600" />
                </a>
            </div>

            <div class="nav-links">

                    {{-- DASHBOARD --}}
                    <x-breeze.nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-breeze.nav-link>


                    {{-- ADMIN MENU --}}
                    @if(auth()->user()->role === 'admin')

                        <x-breeze.nav-link
                            href="{{ route('admin.appointments.index') }}"
                            :active="request()->routeIs('admin.appointments.*')">
                            {{ __('Appointments') }}
                        </x-breeze.nav-link>

                        <x-breeze.nav-link
                            href="{{ route('admin.cabinets.index') }}"
                            :active="request()->routeIs('admin.cabinets.*')">
                            {{ __('Doctors') }}
                        </x-breeze.nav-link>

                        <x-breeze.nav-link
                            href="{{ route('admin.patients.index') }}"
                            :active="request()->routeIs('admin.patients.*')">
                            {{ __('Patients') }}
                        </x-breeze.nav-link>

                    @endif



                    {{-- DOCTOR MENU --}}
                    @if(auth()->user()->role === 'doctor')

                        <x-breeze.nav-link
                            href="{{ route('doctor.appointments.index') }}"
                            :active="request()->routeIs('doctor.appointments.*')">
                            {{ __('Appointments') }}
                        </x-breeze.nav-link>

                        <x-breeze.nav-link
                            href="{{ route('doctor.patients.index') }}"
                            :active="request()->routeIs('doctor.patients.*')">
                            {{ __('My Patients') }}
                        </x-breeze.nav-link>

                    @endif



                    {{-- PATIENT MENU (optional) --}}
                    @if(auth()->user()->role === 'patient')

                        <x-breeze.nav-link
                            href="{{ route('appointments.index') }}"
                            :active="request()->routeIs('appointments.*')">
                            {{ __('My Appointments') }}
                        </x-breeze.nav-link>

                    @endif

            </div>
        </div>

        <div class="nav-user">
            <x-breeze.dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="nav-user-trigger focus-ring">
                        <div class="nav-user-name">{{ Auth::user()->name }}</div>
                        <svg class="nav-user-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-breeze.dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-breeze.dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-breeze.dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-breeze.dropdown-link>
                    </form>
                </x-slot>
            </x-breeze.dropdown>
        </div>
    </div>
</nav>
