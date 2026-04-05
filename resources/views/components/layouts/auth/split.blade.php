<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Force light mode on auth pages — do NOT use @fluxAppearance here --}}
    <style>:root { color-scheme: light; }</style>
    <script>document.documentElement.classList.remove('dark')</script>
</head>

<body class="min-h-screen bg-[#f4f6f5] font-sans antialiased text-zinc-900">

<div class="min-h-screen lg:grid lg:grid-cols-[420px_1fr]">

    {{-- Left panel – brand / illustration --}}
    <aside class="hidden lg:flex flex-col bg-[#324b45] relative overflow-hidden">

        {{-- Decorative circles --}}
        <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-white/[0.04] pointer-events-none"></div>
        <div class="absolute top-1/3 -right-16 w-56 h-56 rounded-full bg-[#DA9100]/10 pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-10 w-80 h-80 rounded-full bg-white/[0.03] pointer-events-none"></div>

        {{-- Logo --}}
        <div class="relative z-10 px-10 pt-10">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3" wire:navigate>
                <div class="w-10 h-10 rounded-xl bg-[#DA9100] flex items-center justify-center shadow-md">
                    <x-app-logo-icon class="w-6 h-6 fill-white" />
                </div>
                <span class="text-white font-bold text-lg">{{ config('app.name', 'Orphanage') }}</span>
            </a>
        </div>

        {{-- Middle content --}}
        <div class="relative z-10 flex-1 flex flex-col justify-center px-10 py-12">
            <div class="mb-8">
                <div class="w-14 h-1.5 bg-[#DA9100] rounded-full mb-6"></div>
                <h1 class="text-3xl font-bold text-white leading-snug mb-4">
                    Caring for children,<br>
                    <span class="text-[#DA9100]">every step</span> of the way.
                </h1>
                <p class="text-white/60 text-sm leading-relaxed max-w-xs">
                    A unified management platform for staff, children, facilities, and resources — designed to make your work easier.
                </p>
            </div>

            {{-- Feature chips --}}
            <div class="space-y-2.5">
                @foreach([
                    ['icon' => 'users', 'text' => 'Child & Staff Management'],
                    ['icon' => 'building-2', 'text' => 'Facility Oversight'],
                    ['icon' => 'heart-handshake', 'text' => 'Donor & Volunteer Tracking'],
                    ['icon' => 'bar-chart-2', 'text' => 'Reports & Analytics'],
                ] as $feature)
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-[#DA9100]/20 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 text-[#DA9100]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            @if($feature['icon'] === 'users')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m0 0a5 5 0 116 0m-6 0a5 5 0 116 0"/>
                            @elseif($feature['icon'] === 'building-2')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11M8 14v3m4-3v3m4-3v3"/>
                            @elseif($feature['icon'] === 'heart-handshake')
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            @endif
                        </svg>
                    </div>
                    <span class="text-white/70 text-sm">{{ $feature['text'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom quote --}}
        <div class="relative z-10 px-10 pb-10 border-t border-white/10 pt-6">
            @php [$message, $author] = str(\Illuminate\Foundation\Inspiring::quotes()->random())->explode('-'); @endphp
            <p class="text-white/50 text-xs italic leading-relaxed">&ldquo;{{ trim($message) }}&rdquo;</p>
            <p class="text-white/35 text-xs mt-1">— {{ trim($author) }}</p>
        </div>
    </aside>

    {{-- Right panel – form --}}
    <main class="flex flex-col items-center justify-center min-h-screen px-6 py-12 lg:px-16 bg-[#f4f6f5] text-zinc-900">

        {{-- Mobile logo (only visible on small screens) --}}
        <div class="mb-8 lg:hidden">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-2" wire:navigate>
                <div class="w-12 h-12 rounded-2xl bg-[#324b45] flex items-center justify-center shadow-md">
                    <x-app-logo-icon class="w-7 h-7 fill-white" />
                </div>
                <span class="font-bold text-[#324b45] text-lg">{{ config('app.name', 'Orphanage') }}</span>
            </a>
        </div>

        <div class="w-full max-w-sm">
            {{ $slot }}
        </div>

        <p class="mt-8 text-xs text-zinc-400">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </main>
</div>

@fluxScripts
</body>
</html>
