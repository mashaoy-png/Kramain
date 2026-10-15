<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kramain') — Unggah-Ungguh Bahasa Jawa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Alpine.js: jalankan `npm install alpinejs` lalu di resources/js/app.js:
         import Alpine from 'alpinejs'; window.Alpine = Alpine; Alpine.start(); --}}

    <style>
        :root{
            --cream:#F7EEDD; --ink:#241A12; --soga:#8B4226; --soga-light:#B5673F;
            --wedel:#1E3A5F; --wedel-deep:#16293F; --gold:#C9A24B;
            --card:#FFFDF8; --line:#E6D7C0; --muted:#786552;
        }
        body{ font-family:'Plus Jakarta Sans', sans-serif; background:var(--cream); color:var(--ink); }
        .font-display{ font-family:'Space Grotesk', sans-serif; }
        .font-mono{ font-family:'IBM Plex Mono', monospace; }
    </style>
</head>
<body class="h-full antialiased">

    {{-- ===== NAVBAR ===== --}}
    <header class="sticky top-0 z-50 backdrop-blur border-b" style="background:rgba(255,253,248,.92); border-color:var(--line)"
            x-data="{ profileOpen: false }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex items-center h-16 gap-4">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 flex-shrink-0">
                <svg class="w-8 h-8" style="color:var(--soga)" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.6"/>
                    <circle cx="24" cy="24" r="16" stroke="currentColor" stroke-width="0.9"/>
                    <g stroke="currentColor" stroke-width="1.2">
                        <ellipse cx="24" cy="15" rx="3.6" ry="6.5"/>
                        <ellipse cx="24" cy="33" rx="3.6" ry="6.5"/>
                        <ellipse cx="15" cy="24" rx="6.5" ry="3.6"/>
                        <ellipse cx="33" cy="24" rx="6.5" ry="3.6"/>
                    </g>
                    <circle cx="24" cy="24" r="2.6" fill="currentColor"/>
                </svg>
                <span class="font-display font-semibold hidden sm:inline tracking-tight">Kramain</span>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-1 ml-4">
                <a href="{{ route('dashboard') }}"
                   class="px-3.5 py-2 text-sm font-medium rounded-full transition-colors"
                   style="{{ request()->routeIs('dashboard') ? 'background:var(--wedel);color:#fff' : 'color:var(--muted)' }}">
                    Peta Level
                </a>
                <a href="{{ route('leaderboard') }}"
                   class="px-3.5 py-2 text-sm font-medium rounded-full transition-colors"
                   style="{{ request()->routeIs('leaderboard') ? 'background:var(--wedel);color:#fff' : 'color:var(--muted)' }}">
                    Peringkat
                </a>
            </nav>

            <div class="flex-1"></div>

            {{-- XP Chip --}}
            <div class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-semibold select-none border"
                 style="background:#FBF3DF; border-color:#EAD9AE; color:var(--soga)">
                <svg class="w-3.5 h-3.5" style="color:var(--gold)" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0
                             00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0
                             00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1
                             1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1
                             1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0
                             00.951-.69l1.07-3.292z"/>
                </svg>
                {{ auth()->user()->totalXp() }} XP
            </div>

            {{-- Profile Dropdown --}}
            <div class="relative" @click.away="profileOpen = false">
                <button @click="profileOpen = !profileOpen"
                        class="w-9 h-9 rounded-full text-white font-semibold text-sm flex items-center
                               justify-center transition hover:opacity-90"
                        style="background:var(--wedel)">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </button>

                <div x-show="profileOpen"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute right-0 mt-2 w-56 rounded-2xl shadow-lg border py-1.5 z-50"
                     style="background:var(--card); border-color:var(--line); display:none;">
                    <div class="px-4 py-2.5 border-b" style="border-color:var(--line)">
                        <p class="text-sm font-medium truncate" style="color:var(--ink)">{{ auth()->user()->name }}</p>
                        <p class="text-xs truncate" style="color:var(--muted)">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('profile') }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-sm transition hover:opacity-80"
                       style="color:var(--ink)">
                        <svg class="w-4 h-4" style="color:var(--muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Saya
                    </a>
                    @if(auth()->user()->certificate_available ?? false)
                    <a href="{{ route('certificate') }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-sm transition hover:opacity-80"
                       style="color:var(--ink)">
                        <svg class="w-4 h-4" style="color:var(--muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Sertifikat Saya
                    </a>
                    @endif
                    <hr class="my-1" style="border-color:var(--line)">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-600
                                       hover:bg-red-50 transition text-left">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0
                                         01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-5">
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 flex items-center gap-3" role="alert">
            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1
                      0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0
                      001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-5">
        <div class="bg-red-50 border border-red-200 rounded-2xl px-4 py-3 flex items-center gap-3" role="alert">
            <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0
                      112 0v-1a1 1 0 11-2 0v1zm0-4a1 1 0 112 0V7a1 1 0 11-2 0v2z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 pb-24 md:pb-10 min-h-screen">
        @yield('content')
    </main>

    {{-- ===== MOBILE BOTTOM NAV ===== --}}
    <nav class="md:hidden fixed bottom-0 inset-x-0 border-t z-40" style="background:var(--card); border-color:var(--line)">
        <div class="flex">
            <a href="{{ route('dashboard') }}"
               class="flex-1 flex flex-col items-center gap-0.5 pt-3 pb-2.5 text-xs font-medium transition"
               style="color:{{ request()->routeIs('dashboard') ? 'var(--wedel)' : 'var(--muted)' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z
                             M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z
                             M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z
                             M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Level
            </a>
            <a href="{{ route('leaderboard') }}"
               class="flex-1 flex flex-col items-center gap-0.5 pt-3 pb-2.5 text-xs font-medium transition"
               style="color:{{ request()->routeIs('leaderboard') ? 'var(--wedel)' : 'var(--muted)' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0
                             002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2
                             2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2
                             2 0 01-2-2z"/>
                </svg>
                Peringkat
            </a>
            <a href="{{ route('profile') }}"
               class="flex-1 flex flex-col items-center gap-0.5 pt-3 pb-2.5 text-xs font-medium transition"
               style="color:{{ request()->routeIs('profile') ? 'var(--wedel)' : 'var(--muted)' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil
            </a>
        </div>
    </nav>

    @stack('scripts')
</body>
</html>
