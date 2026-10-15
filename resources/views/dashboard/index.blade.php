@extends('layouts.app')

@section('title', 'Peta Level')

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8">
    <h1 class="font-display font-bold text-2xl mb-1" style="color:var(--ink)">
        Halo, {{ auth()->user()->name }} 👋
    </h1>
    <p style="color:var(--muted)">Pilih level untuk melanjutkan perjalanan belajar unggah-ungguh kamu.</p>
</div>

{{-- ===== XP PROGRESS ===== --}}
<div class="rounded-2xl border p-5 mb-8" style="background:var(--card); border-color:var(--line)">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" style="color:var(--gold)" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969
                         0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755
                         1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197
                         -1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81
                         .588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <span class="font-mono text-xs uppercase tracking-widest" style="color:var(--soga)">Total XP</span>
        </div>
        <span class="font-display font-bold text-2xl" style="color:var(--soga)">
            {{ $totalXp }}<span class="text-sm font-normal" style="color:var(--muted)"> / 500</span>
        </span>
    </div>
    <div class="h-2.5 rounded-full overflow-hidden" style="background:var(--line)">
        <div class="h-full rounded-full transition-all duration-500"
             style="width: {{ min(100, ($totalXp / 500) * 100) }}%; background:linear-gradient(90deg, var(--wedel), var(--gold))"></div>
    </div>
    <div class="flex items-center justify-between mt-3 text-xs" style="color:var(--muted)">
        <span>{{ $completedCount }} dari 5 level selesai</span>
        <span>{{ 500 - $totalXp }} XP lagi untuk lulus semua level</span>
    </div>
</div>

{{-- ===== PETA LEVEL ===== --}}
<div class="space-y-4">
    @foreach($levels as $level)
    @php
        $progress = $userProgress[$level->id] ?? null;
        $status   = $progress->status ?? 'locked';   // locked | unlocked | completed
        $score    = $progress->score   ?? null;
        $isLocked    = $status === 'locked';
        $isCompleted = $status === 'completed';
        $isUnlocked  = $status === 'unlocked';

        // Ramp warna sama seperti fan kartu di landing page: indigo (informal) -> emas (formal/sertifikat)
        $swatch = [
            1 => ['bg'=>'#E7EEF5', 'border'=>'#C9D9E8', 'text'=>'#1E3A5F'],
            2 => ['bg'=>'#DDE1E7', 'border'=>'#C4CCD3', 'text'=>'#2B3A4A'],
            3 => ['bg'=>'#DCCFC2', 'border'=>'#C9B8A8', 'text'=>'#5A3A2A'],
            4 => ['bg'=>'#D8B896', 'border'=>'#C7A276', 'text'=>'#4A2E1A'],
            5 => ['bg'=>'#C9A24B', 'border'=>'#B08B3A', 'text'=>'#241A12'],
        ][$level->level_number] ?? ['bg'=>'#E7EEF5','border'=>'#C9D9E8','text'=>'#1E3A5F'];
    @endphp

    <div class="rounded-2xl p-5 sm:p-6 border transition hover:shadow-sm {{ $isLocked ? 'opacity-70' : '' }}"
         style="background:var(--card); border-color:{{ $isCompleted ? '#A7D8C0' : ($isLocked ? 'var(--line)' : $swatch['border']) }}">
        <div class="flex items-start gap-4">

            {{-- Nomor level --}}
            <div class="flex-shrink-0">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-display font-bold text-lg"
                     style="background:{{ $isLocked ? 'var(--line)' : $swatch['bg'] }}; color:{{ $isLocked ? 'var(--muted)' : $swatch['text'] }}">
                    @if($isCompleted)
                        <svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1
                                  1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1
                                  0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($isLocked)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0
                                     002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    @else
                        {{ $level->level_number }}
                    @endif
                </div>
            </div>

            {{-- Konten level --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3 flex-wrap">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-display font-semibold" style="color:var(--ink)">
                                Level {{ $level->level_number }} — {{ $level->name }}
                            </h3>
                            @if($isCompleted)
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full border"
                                  style="color:#0F7A54; background:#E7F6EF; border-color:#BFE6D5">Selesai</span>
                            @elseif($isUnlocked)
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full border"
                                  style="color:var(--wedel); background:#EAF0F6; border-color:#C9D9E8">Aktif</span>
                            @else
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full border"
                                  style="color:var(--muted); background:var(--cream); border-color:var(--line)">Terkunci</span>
                            @endif
                        </div>
                        <p class="text-sm mt-1" style="color:var(--muted)">{{ $level->description }}</p>

                        @if($score !== null)
                        <div class="flex items-center gap-3 mt-2 text-xs">
                            <span style="color:var(--muted)">Skor terakhir:</span>
                            <span class="font-semibold {{ $score >= 75 ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $score }}%
                            </span>
                            @if($score >= 75)
                            <span class="font-medium" style="color:var(--soga)">+{{ $progress->xp_earned }} XP</span>
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- XP Reward --}}
                    <div class="text-right flex-shrink-0">
                        <p class="font-mono text-[11px] uppercase tracking-widest" style="color:var(--muted)">Hadiah</p>
                        <p class="font-display font-bold" style="color:var(--soga)">+{{ $level->xp_reward }} XP</p>
                    </div>
                </div>

                @if($isCompleted && $score !== null)
                <div class="mt-3">
                    <div class="h-1.5 rounded-full overflow-hidden" style="background:var(--line)">
                        <div class="h-full bg-emerald-400 rounded-full" style="width: {{ $score }}%"></div>
                    </div>
                </div>
                @endif

                {{-- Action buttons --}}
                <div class="mt-4 flex items-center gap-2 flex-wrap">
                    @if($isLocked)
                    <div class="text-xs flex items-center gap-1" style="color:var(--muted)">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Selesaikan Level {{ $level->level_number - 1 }} dengan skor ≥ 75% untuk membuka
                    </div>
                    @elseif($isCompleted)
                    <a href="{{ route('levels.show', $level->id) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-full border transition hover:opacity-80"
                       style="color:var(--soga); border-color:var(--soga)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0
                                     0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Ulangi Level
                    </a>
                    @else
                    <a href="{{ route('levels.show', $level->id) }}"
                       class="inline-flex items-center gap-1.5 px-5 py-2.5 text-sm font-semibold text-white
                              rounded-full transition hover:opacity-90"
                       style="background:var(--wedel)">
                        Mulai Belajar
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endif

                    @if($level->level_number === 5 && $isCompleted)
                    <a href="{{ route('certificate') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-full transition hover:opacity-90"
                       style="background:var(--gold); color:var(--ink)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955
                                     0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622
                                     5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Unduh Sertifikat
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Konektor antar level --}}
    @if(!$loop->last)
    <div class="flex justify-center">
        <div class="w-0.5 h-4" style="background:{{ $isCompleted ? '#A7D8C0' : 'var(--line)' }}"></div>
    </div>
    @endif

    @endforeach
</div>

@endsection
