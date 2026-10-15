<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Kramain</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
<body class="h-full antialiased flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="flex justify-center mb-8">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <svg class="w-10 h-10" style="color:var(--soga)" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.5"/>
                    <circle cx="24" cy="24" r="16" stroke="currentColor" stroke-width="0.9"/>
                    <g stroke="currentColor" stroke-width="1.2">
                        <ellipse cx="24" cy="15" rx="3.8" ry="6.8"/>
                        <ellipse cx="24" cy="33" rx="3.8" ry="6.8"/>
                        <ellipse cx="15" cy="24" rx="6.8" ry="3.8"/>
                        <ellipse cx="33" cy="24" rx="6.8" ry="3.8"/>
                    </g>
                    <circle cx="24" cy="24" r="2.6" fill="currentColor"/>
                </svg>
                <div>
                    <p class="font-display font-semibold leading-tight" style="color:var(--ink)">Kramain</p>
                    <p class="text-xs leading-tight" style="color:var(--muted)">Unggah-Ungguh Bahasa Jawa</p>
                </div>
            </a>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl shadow-sm border p-8" style="background:var(--card); border-color:var(--line)">
            @yield('content')
        </div>

        <p class="text-center text-xs mt-6" style="color:var(--muted)">
            Media Pembelajaran Bahasa Jawa · S1 Informatika UAD 2025/2026
        </p>
    </div>

</body>
</html>
