@extends('layouts.app')

@section('title', 'Hasil Kuis Level ' . $level->level_number)

@section('content')

<div class="max-w-lg mx-auto">

    <div class="text-center mb-8">
        <div class="relative inline-flex items-center justify-center mb-6">
            <svg class="w-36 h-36 -rotate-90" viewBox="0 0 120 120">
                <circle cx="60" cy="60" r="50" fill="none" stroke="#E6D7C0" stroke-width="10"/>
                <circle cx="60" cy="60" r="50" fill="none"
                        stroke="{{ $passed ? '#10B981' : '#EF4444' }}" stroke-width="10"
                        stroke-linecap="round"
                        stroke-dasharray="{{ round($score / 100 * 314.16, 1) }} 314.16"
                        class="transition-all duration-1000"/>
            </svg>
            <div class="absolute text-center">
                <p class="text-3xl font-display font-bold {{ $passed ? 'text-emerald-600' : 'text-red-500' }}">
                    {{ $score }}%
                </p>
                <p class="text-xs mt-0.5" style="color:var(--muted)">Skor Kamu</p>
            </div>
        </div>

        @if($passed)
        <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 font-semibold
                    px-4 py-2 rounded-full border border-emerald-200 mb-3">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1
                      0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0
                      001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Lulus! Skor ≥ 75%
        </div>
        <h1 class="font-display font-bold text-2xl" style="color:var(--ink)">Bagus sekali! 🎉</h1>
        <p class="mt-2" style="color:var(--muted)">Kamu telah menguasai {{ $level->name }} dengan baik.</p>
        @else
        <div class="inline-flex items-center gap-2 bg-red-50 text-red-700 font-semibold
                    px-4 py-2 rounded-full border border-red-200 mb-3">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 112 0v-1a1 1 0
                      11-2 0v1zm0-4a1 1 0 112 0V7a1 1 0 11-2 0v2z" clip-rule="evenodd"/>
            </svg>
            Belum lulus · Skor kurang dari 75%
        </div>
        <h1 class="font-display font-bold text-2xl" style="color:var(--ink)">Jangan menyerah! 💪</h1>
        <p class="mt-2" style="color:var(--muted)">Pelajari kembali materinya dan coba lagi.</p>
        @endif
    </div>

    <div class="rounded-2xl border divide-y mb-6" style="background:var(--card); border-color:var(--line)">
        <div class="flex items-center justify-between px-5 py-4" style="border-color:var(--line)">
            <span class="text-sm" style="color:var(--muted)">Skor kamu</span>
            <span class="font-semibold {{ $passed ? 'text-emerald-600' : 'text-red-500' }}">
                {{ $score }}% (minimum 75%)
            </span>
        </div>
        <div class="flex items-center justify-between px-5 py-4" style="border-color:var(--line)">
            <span class="text-sm" style="color:var(--muted)">XP diraih</span>
            @if($passed)
            <span class="font-semibold flex items-center gap-1.5" style="color:var(--soga)">
                <svg class="w-4 h-4" style="color:var(--gold)" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969
                             0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755
                             1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197
                             -1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81
                             .588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                +{{ $xpEarned }} XP
            </span>
            @else
            <span class="text-sm" style="color:var(--muted)">— (lulus untuk mendapat XP)</span>
            @endif
        </div>
        <div class="flex items-center justify-between px-5 py-4" style="border-color:var(--line)">
            <span class="text-sm" style="color:var(--muted)">Level ini</span>
            <span class="text-sm font-medium" style="color:var(--ink)">
                Level {{ $level->level_number }} — {{ $level->name }}
            </span>
        </div>
        <div class="flex items-center justify-between px-5 py-4">
            <span class="text-sm" style="color:var(--muted)">Status level berikutnya</span>
            @if($passed && $nextLevel)
            <span class="text-sm font-medium" style="color:var(--wedel)">✓ Level {{ $nextLevel->level_number }} terbuka</span>
            @elseif($passed && !$nextLevel)
            <span class="text-sm font-medium" style="color:var(--soga)">✓ Semua level selesai!</span>
            @else
            <span class="text-sm" style="color:var(--muted)">Masih terkunci</span>
            @endif
        </div>
    </div>

    @if($passed && $badge)
    <div class="rounded-2xl p-6 mb-6 text-center border" style="background:linear-gradient(135deg,#FBF3DF,#F7EEDD); border-color:#EAD9AE">
        <p class="text-xs font-semibold uppercase tracking-widest mb-3" style="color:var(--soga)">
            Lencana Baru Diraih! 🏅
        </p>
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl border-2"
             style="background:#FBF3DF; border-color:#EAD9AE">
            🏅
        </div>
        <p class="font-display font-bold mb-1" style="color:var(--ink)">{{ $badge->name }}</p>
        <p class="text-sm" style="color:var(--muted)">{{ $badge->description }}</p>
    </div>
    @endif

    @if($passed && $level->level_number === 5)
    <div class="rounded-2xl p-6 mb-6 text-white text-center" style="background:linear-gradient(135deg,var(--wedel),var(--soga))">
        <p class="text-2xl mb-2">🎓</p>
        <h3 class="font-display font-bold text-lg mb-1">Selamat! Kamu telah lulus semua level!</h3>
        <p class="text-sm mb-4" style="color:#E7D9C7">Sertifikat penyelesaian sudah siap diunduh.</p>
        <a href="{{ route('certificate') }}"
           class="inline-block px-6 py-2.5 font-semibold text-sm rounded-full transition hover:opacity-90"
           style="background:var(--gold); color:var(--ink)">
            Unduh Sertifikat
        </a>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('levels.show', $level->id) }}"
           class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium
                  rounded-full border transition hover:opacity-80" style="color:var(--soga); border-color:var(--soga)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0
                         0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            {{ $passed ? 'Ulangi Level' : 'Pelajari Lagi' }}
        </a>

        @if($passed && $nextLevel)
        <a href="{{ route('levels.show', $nextLevel->id) }}"
           class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold
                  text-white rounded-full transition hover:opacity-90" style="background:var(--wedel)">
            Level {{ $nextLevel->level_number }}: {{ $nextLevel->name }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @elseif($passed && !$nextLevel)
        <a href="{{ route('dashboard') }}"
           class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold
                  text-white bg-emerald-600 rounded-full hover:bg-emerald-700 transition">
            Lihat Pencapaian Kamu
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @else
        <a href="{{ route('levels.quiz', $level->id) }}"
           class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold
                  text-white rounded-full transition hover:opacity-90" style="background:var(--wedel)">
            Coba Lagi
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('dashboard') }}" class="text-sm hover:opacity-70 transition" style="color:var(--muted)">
            ← Kembali ke Peta Level
        </a>
    </div>

</div>
@endsection
