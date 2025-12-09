<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <style>
        .sidebar-text a{
            color: #DA9100; /* Tailwind's text-neutral-300 */
        }
        .sidebar-text:hover {
            color: black; /* White on hover */
        }
        .daralamah-green {
            background-color: #324b45; /* Dark green color */
        }
    </style>
    <head>
        @include('partials.head')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-green-950 sidebar-text">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            <a href="{{ route('dashboardoms') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('children.index')" :current="request()->routeIs('children.*')" wire:navigate>{{ __('Children') }}</flux:navlist.item>
                    @if(auth()->user()->isAdmin() || auth()->user()->isCaregiver())
                    <flux:navlist.item icon="building-library" :href="route('facilities.index')" :current="request()->routeIs('facilities.*')" wire:navigate> {{__('Facilities')}}</flux:navlist.item>
                    <flux:navlist.item icon="building-library" :href="route('volunteers.index')" :current="request()->routeIs('volunteers.*')" wire:navigate> {{__('Volunteers')}}</flux:navlist.item>
                    @endif
                    @if(auth()->user()->isAdmin() || auth()->user()->isCaregiver())
                    <flux:navlist.item icon="building-library" :href="route('donors.index')" :current="request()->routeIs('donors.*')" wire:navigate> {{__('Donors')}}</flux:navlist.item>
                    @endif
                    @if(auth()->user()->isAdmin())
                    <flux:navlist.item icon="user-group" :href="route('staff.index')" :current="request()->routeIs('staff.*')" wire:navigate> {{__('Staff')}}</flux:navlist.item>
                    @endif
                    <flux:navlist.item icon="building-library" :href="route('maintenance.index')" :current="request()->routeIs('maintenance.*')" wire:navigate> {{__('Maintenance')}}</flux:navlist.item>
                    <flux:navlist.item icon="building-library" :href="route('documents.index')" :current="request()->routeIs('documents.*')" wire:navigate> {{__('Documents')}}</flux:navlist.item>

                                    
                    
                    <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2">

                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('reports.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium text-neutral-100 hover:bg-primary-700 rounded-lg transition-colors duration-200" 
                       id="nav-reports">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                        Reports
                    </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                    <a href="#" 
                       class="flex items-center px-4 py-3 text-sm font-medium text-neutral-100 hover:bg-primary-700 rounded-lg transition-colors duration-200" 
                       id="nav-settings">
                        <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                        Settings
                    </a>
                    @endif
                </nav>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
