<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- App is light-mode only — do NOT use @fluxAppearance --}}
    <style>:root { color-scheme: light; }</style>
    <script>document.documentElement.classList.remove('dark')</script>
</head>

<body class="min-h-screen bg-[#f4f6f5] text-zinc-900 antialiased">

<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <div
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-[#324b45] transform transition-transform duration-300 ease-in-out
               lg:translate-x-0 lg:static lg:inset-0 flex flex-col">

        <flux:sidebar sticky stashable class="border-e-0 !bg-[#324b45] !w-64 flex flex-col">
               {{-- Mobile close --}}
    <flux:sidebar.toggle class="lg:hidden self-end m-3 text-white/60 hover:text-white" icon="x-mark" />

    {{-- Logo --}}
    <div class="px-5 pt-6 pb-5 border-b border-white/10">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3" wire:navigate>
            <div class="w-9 h-9 rounded-xl bg-[#DA9100] flex items-center justify-center shadow-sm shrink-0">
                <x-app-logo-icon class="w-5 h-5 fill-white" />
            </div>
            <div class="min-w-0">
                <p class="text-white font-bold text-sm leading-tight truncate">{{ config('app.name', 'Orphanage') }}</p>
                <p class="text-white/40 text-[11px] leading-tight">Management System</p>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-5">

        {{-- Main section --}}
        <div>
            <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-widest text-white/35 select-none">Main</p>
            <flux:navlist variant="outline">
                <flux:navlist.item
                    icon="squares-2x2"
                    :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    Dashboard
                </flux:navlist.item>

                <flux:navlist.item
                    icon="face-smile"
                    :href="route('children.index')"
                    :current="request()->routeIs('children.*')"
                    wire:navigate>
                    Children
                </flux:navlist.item>

                @if(auth()->user()->isAdmin() || auth()->user()->isCaregiver())
                <flux:navlist.item
                    icon="building-office-2"
                    :href="route('facilities.index')"
                    :current="request()->routeIs('facilities.*')"
                    wire:navigate>
                    Facilities
                </flux:navlist.item>

                <flux:navlist.item
                    icon="hand-raised"
                    :href="route('volunteers.index')"
                    :current="request()->routeIs('volunteers.*')"
                    wire:navigate>
                    Volunteers
                </flux:navlist.item>

                <flux:navlist.item
                    icon="heart"
                    :href="route('donors.index')"
                    :current="request()->routeIs('donors.*')"
                    wire:navigate>
                    Donors
                </flux:navlist.item>
                @endif
            </flux:navlist>
        </div>

        {{-- Management section --}}
        <div>
            <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-widest text-white/35 select-none">Management</p>
            <flux:navlist variant="outline">
                @if(auth()->user()->isAdmin())
                <flux:navlist.item
                    icon="user-group"
                    :href="route('staff.index')"
                    :current="request()->routeIs('staff.*')"
                    wire:navigate>
                    Staff
                </flux:navlist.item>
                @endif

                <flux:navlist.item
                    icon="wrench-screwdriver"
                    :href="route('maintenance.index')"
                    :current="request()->routeIs('maintenance.*')"
                    wire:navigate>
                    Maintenance
                    @php $pending = \App\Models\MaintenanceRequest::pending()->count(); @endphp
                    @if($pending > 0)
                        <span class="ml-auto min-w-[1.25rem] h-5 px-1.5 rounded-full bg-[#DA9100] text-white text-[10px] font-bold flex items-center justify-center">
                            {{ $pending > 99 ? '99+' : $pending }}
                        </span>
                    @endif
                </flux:navlist.item>

                <flux:navlist.item
                    icon="folder-open"
                    :href="route('documents.index')"
                    :current="request()->routeIs('documents.*')"
                    wire:navigate>
                    Documents
                </flux:navlist.item>

                @if(auth()->user()->isAdmin())
                <flux:navlist.item
                    icon="chart-bar"
                    :href="route('reports.index')"
                    :current="request()->routeIs('reports.*')"
                    wire:navigate>
                    Reports
                </flux:navlist.item>
                @endif
            </flux:navlist>
        </div>
    </div>

    {{-- User profile --}}
    <div class="border-t border-white/10 px-3 py-3">
        <flux:dropdown position="top" align="start" class="w-full">
            <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition-colors group">
                <div class="w-8 h-8 rounded-lg bg-[#DA9100]/30 flex items-center justify-center text-[#DA9100] font-bold text-sm shrink-0">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="flex-1 min-w-0 text-left">
                    <p class="text-white text-sm font-medium leading-tight truncate">{{ auth()->user()->name }}</p>
                    <p class="text-white/40 text-xs leading-tight truncate">{{ auth()->user()->email }}</p>
                </div>
                <svg class="w-4 h-4 text-white/40 group-hover:text-white/70 transition-colors shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                </svg>
            </button>

            <flux:menu class="w-56">
                <flux:menu.radio.group>
                    <div class="flex items-center gap-2.5 px-3 py-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#324b45] flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-zinc-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.item :href="route('profile.edit')" icon="cog-6-tooth" wire:navigate>
                    Account Settings
                </flux:menu.item>
                <flux:menu.item :href="route('appearance.edit')" icon="swatch" wire:navigate>
                    Appearance
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-red-600 hover:bg-red-50">
                        Sign Out
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </div>
        </flux:sidebar>
    </div>

    <!-- Overlay (mobile only) -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/40 z-40 lg:hidden"
        x-transition.opacity>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Header -->
        <div class="lg:hidden">
            <flux:header class="bg-[#324b45] shadow-sm">

                <!-- Toggle button -->
                <button
                    @click="sidebarOpen = true"
                    class="text-white/70 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <flux:spacer />

                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white font-semibold text-sm">
                    <div class="w-6 h-6 rounded-md bg-[#DA9100] flex items-center justify-center">
                        <x-app-logo-icon class="w-3.5 h-3.5 fill-white" />
                    </div>
                    {{ config('app.name', 'Orphanage') }}
                </a>

                <flux:spacer />

                <div class="w-8 h-8 rounded-lg bg-[#DA9100]/20 flex items-center justify-center text-[#DA9100] font-bold text-sm">
                    {{ auth()->user()->initials() }}
                </div>

            </flux:header>
        </div>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8">
            {{ $slot }}
        </main>

    </div>

</div>

<!-- AlpineJS (required for toggle) -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Lucide -->
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    document.addEventListener('livewire:navigated', () => lucide.createIcons());
</script>

@fluxScripts
</body>
</html>
