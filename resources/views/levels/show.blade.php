@extends('layouts.app')

@section('title', 'Level ' . $level->level_number . ' — ' . $level->name)

@section('content')

<nav class="flex items-center gap-1.5 text-sm mb-6" style="color:var(--muted)" aria-label="Breadcrumb">
    <a href="{{ route('dashboard') }}" class="hover:opacity-70 transition">Peta Level</a>
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span style="color:var(--ink)">Level {{ $level->level_number }} — {{ $level->name }}</span>
</nav>

{{-- Header Level --}}
<div class="rounded-2xl border p-6 sm:p-8 mb-6" style="background:var(--card); border-color:var(--line)">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <span class="font-mono text-xs uppercase tracking-widest" style="color:var(--soga)">
                Level {{ $level->level_number }}
            </span>
            <h1 class="font-display font-bold text-2xl mt-1" style="color:var(--ink)">{{ $level->name }}</h1>
            <p class="mt-2 leading-relaxed max-w-2xl" style="color:var(--muted)">{{ $level->description }}</p>
        </div>
        <div class="text-right flex-shrink-0">
            <p class="font-mono text-[11px] uppercase tracking-widest" style="color:var(--muted)">Hadiah selesai</p>
            <p class="font-display font-bold text-2xl" style="color:var(--soga)">+{{ $level->xp_reward }} XP</p>
            <p class="text-xs mt-0.5" style="color:var(--muted)">Skor minimum 75%</p>
        </div>
    </div>

    @if(isset($userProgress) && $userProgress->score !== null)
    <div class="mt-5 pt-5 border-t" style="border-color:var(--line)">
        <div class="flex items-center justify-between text-sm mb-2">
            <span style="color:var(--muted)">Skor terakhir kamu</span>
            <span class="font-semibold {{ $userProgress->score >= 75 ? 'text-emerald-600' : 'text-red-500' }}">
                {{ $userProgress->score }}%
            </span>
        </div>
        <div class="h-2 rounded-full overflow-hidden" style="background:var(--line)">
            <div class="h-full rounded-full {{ $userProgress->score >= 75 ? 'bg-emerald-400' : 'bg-red-400' }}"
                 style="width: {{ $userProgress->score }}%"></div>
        </div>
    </div>
    @endif
</div>

{{-- ===== MATERI ===== --}}
<div class="mb-6">
    <h2 class="font-display font-semibold text-lg mb-4" style="color:var(--ink)">
        <span class="inline-block w-1 h-5 rounded-full mr-2 align-middle" style="background:var(--wedel)"></span>
        Materi Pembelajaran
    </h2>
    <div class="space-y-4">
        @foreach($materials as $material)
        <div class="rounded-2xl border p-6" style="background:var(--card); border-color:var(--line)">
            <h3 class="font-display font-semibold mb-3" style="color:var(--ink)">{{ $material->title }}</h3>
            <div class="prose prose-sm max-w-none leading-relaxed" style="color:var(--muted)">
                {!! nl2br(e($material->content)) !!}
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ===== SKENARIO PERCAKAPAN ===== --}}
@php $materialsWithScenario = $materials->whereNotNull('scenario')->where('scenario', '!=', ''); @endphp
@if($materialsWithScenario->isNotEmpty())
<div class="mb-8">
    <h2 class="font-display font-semibold text-lg mb-4" style="color:var(--ink)">
        <span class="inline-block w-1 h-5 rounded-full mr-2 align-middle" style="background:var(--gold)"></span>
        Skenario Percakapan Kontekstual
    </h2>
    <div class="space-y-4">
        @foreach($materialsWithScenario as $material)
        <div class="rounded-2xl p-6 border" style="background:#FBF3DF; border-color:#EAD9AE">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4" style="color:var(--soga)" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0
                             01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8
                             9-8s9 3.582 9 8z"/>
                </svg>
                <span class="text-sm font-semibold" style="color:var(--soga)">Contoh Percakapan</span>
            </div>
            <div class="text-sm leading-relaxed whitespace-pre-line font-mono rounded-xl p-4 border"
                 style="color:var(--ink); background:var(--card); border-color:#EAD9AE">{{ $material->scenario }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ===== CTA MULAI KUIS ===== --}}
<div class="rounded-2xl p-6 sm:p-8" style="background:var(--wedel)">
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div class="text-white">
            <h3 class="font-display font-bold text-lg">Siap mengerjakan kuis?</h3>
            <p class="text-sm mt-1" style="color:#C9D9E8">
                Raih skor ≥ 75% untuk mendapatkan {{ $level->xp_reward }} XP dan membuka level berikutnya.
            </p>
        </div>
        <a href="{{ route('levels.quiz', $level->id) }}"
           class="flex-shrink-0 px-6 py-3 font-semibold text-sm rounded-full transition hover:opacity-90 active:scale-95"
           style="background:#fff; color:var(--wedel)">
            Mulai Kuis →
        </a>
    </div>
</div>

@endsection
