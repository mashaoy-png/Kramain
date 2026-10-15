@extends('layouts.app')

@section('title', 'Papan Peringkat')

@section('content')

<div class="mb-6">
    <h1 class="font-display font-bold text-2xl" style="color:var(--ink)">Papan Peringkat</h1>
    <p class="mt-1" style="color:var(--muted)">Peringkat berdasarkan total XP yang dikumpulkan.</p>
</div>

@if($leaderboard->count() >= 3)
<div class="rounded-2xl border p-6 mb-6" style="background:var(--card); border-color:var(--line)">
    <p class="font-mono text-xs uppercase tracking-widest text-center mb-6" style="color:var(--soga)">
        Top 3 Pengguna
    </p>
    <div class="flex items-end justify-center gap-3">

        @php $second = $leaderboard->get(1); @endphp
        <div class="flex-1 max-w-28 text-center">
            <div class="w-12 h-12 rounded-full font-display font-bold text-lg flex items-center justify-center
                        mx-auto mb-2 border-2" style="background:var(--cream); color:var(--muted); border-color:var(--line)">
                {{ strtoupper(substr($second->name, 0, 1)) }}
            </div>
            <p class="text-xs font-medium truncate" style="color:var(--ink)">{{ $second->name }}</p>
            <p class="text-sm font-display font-bold mt-0.5" style="color:var(--muted)">{{ $second->total_xp }} XP</p>
            <div class="mt-3 h-16 rounded-t-xl flex items-center justify-center" style="background:var(--cream)">
                <span class="text-2xl">🥈</span>
            </div>
        </div>

        @php $first = $leaderboard->get(0); @endphp
        <div class="flex-1 max-w-32 text-center -mt-4">
            <div class="w-14 h-14 rounded-full font-display font-bold text-xl flex items-center justify-center
                        mx-auto mb-2 border-2" style="background:#FBF3DF; color:var(--soga); border-color:#EAD9AE">
                {{ strtoupper(substr($first->name, 0, 1)) }}
            </div>
            <p class="text-sm font-semibold truncate" style="color:var(--ink)">{{ $first->name }}</p>
            <p class="text-base font-display font-bold mt-0.5" style="color:var(--soga)">{{ $first->total_xp }} XP</p>
            <div class="mt-3 h-24 rounded-t-xl flex items-center justify-center border" style="background:#FBF3DF; border-color:#EAD9AE">
                <span class="text-3xl">🥇</span>
            </div>
        </div>

        @php $third = $leaderboard->get(2); @endphp
        <div class="flex-1 max-w-28 text-center">
            <div class="w-12 h-12 rounded-full font-display font-bold text-lg flex items-center justify-center
                        mx-auto mb-2 border-2" style="background:#F3E4DA; color:var(--soga-light); border-color:#E0C4B0">
                {{ strtoupper(substr($third->name, 0, 1)) }}
            </div>
            <p class="text-xs font-medium truncate" style="color:var(--ink)">{{ $third->name }}</p>
            <p class="text-sm font-display font-bold mt-0.5" style="color:var(--soga-light)">{{ $third->total_xp }} XP</p>
            <div class="mt-3 h-10 rounded-t-xl flex items-center justify-center border" style="background:#F3E4DA; border-color:#E0C4B0">
                <span class="text-2xl">🥉</span>
            </div>
        </div>
    </div>
</div>
@endif

@if($currentUserRank > 3)
<div class="rounded-2xl px-5 py-4 mb-4 flex items-center justify-between gap-4 border" style="background:#EAF0F6; border-color:#C9D9E8">
    <div class="flex items-center gap-3">
        <span class="text-lg font-display font-bold" style="color:var(--wedel)">#{{ $currentUserRank }}</span>
        <div class="w-9 h-9 rounded-full font-semibold text-sm flex items-center justify-center"
             style="background:var(--wedel); color:#fff">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
            <p class="text-sm font-semibold" style="color:var(--wedel)">{{ auth()->user()->name }}</p>
            <p class="text-xs" style="color:var(--muted)">Posisimu saat ini</p>
        </div>
    </div>
    <span class="font-display font-bold" style="color:var(--soga)">{{ auth()->user()->totalXp() }} XP</span>
</div>
@endif

<div class="rounded-2xl border overflow-hidden" style="background:var(--card); border-color:var(--line)">
    <div class="px-5 py-4 border-b" style="border-color:var(--line)">
        <h2 class="font-display font-semibold text-sm" style="color:var(--ink)">Semua Pengguna</h2>
    </div>
    <div class="divide-y" style="border-color:var(--line)">
        @forelse($leaderboard as $idx => $user)
        @php
            $rank       = $idx + 1;
            $isSelf     = $user->id === auth()->id();
            $isTop3     = $rank <= 3;
            $rankEmoji  = match($rank) { 1 => '🥇', 2 => '🥈', 3 => '🥉', default => '' };
        @endphp
        <div class="flex items-center gap-4 px-5 py-4 transition" style="{{ $isSelf ? 'background:#EAF0F6' : '' }}">
            <div class="w-8 text-center flex-shrink-0">
                @if($isTop3)
                <span class="text-lg">{{ $rankEmoji }}</span>
                @else
                <span class="text-sm font-semibold" style="color:var(--muted)">#{{ $rank }}</span>
                @endif
            </div>

            <div class="w-9 h-9 rounded-full flex items-center justify-center font-semibold text-sm flex-shrink-0"
                 style="{{ $isSelf ? 'background:var(--wedel); color:#fff' : 'background:var(--cream); color:var(--muted)' }}">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate" style="color:var(--ink)">
                    {{ $user->name }}
                    @if($isSelf)
                    <span class="ml-1 text-xs font-normal" style="color:var(--wedel)">(kamu)</span>
                    @endif
                </p>
                <div class="flex items-center gap-3 mt-1">
                    <div class="flex-1 max-w-32 h-1.5 rounded-full overflow-hidden" style="background:var(--line)">
                        <div class="h-full rounded-full" style="width: {{ min(100, ($user->total_xp / 500) * 100) }}%; background:var(--gold)"></div>
                    </div>
                    <span class="text-xs" style="color:var(--muted)">{{ $user->completed_count ?? 0 }} level selesai</span>
                </div>
            </div>

            <div class="text-right flex-shrink-0">
                <p class="font-display font-bold" style="color:var(--soga)">{{ $user->total_xp }}</p>
                <p class="text-xs" style="color:var(--muted)">XP</p>
            </div>
        </div>
        @empty
        <div class="text-center py-12" style="color:var(--muted)">
            <p class="text-2xl mb-2">📊</p>
            <p class="text-sm">Belum ada data peringkat.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
