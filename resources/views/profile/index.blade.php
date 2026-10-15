@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

<h1 class="font-display font-bold text-2xl mb-6" style="color:var(--ink)">Profil Saya</h1>

<div class="grid lg:grid-cols-3 gap-6">

    <div class="lg:col-span-1 space-y-4">

        <div class="rounded-2xl border p-6 text-center" style="background:var(--card); border-color:var(--line)">
            <div class="w-16 h-16 rounded-full text-white font-display font-bold text-2xl
                        flex items-center justify-center mx-auto mb-3" style="background:var(--wedel)">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 class="font-display font-bold text-lg" style="color:var(--ink)">{{ $user->name }}</h2>
            <p class="text-sm" style="color:var(--muted)">{{ $user->email }}</p>
            <p class="text-xs mt-1" style="color:var(--muted)">Bergabung {{ $user->created_at->translatedFormat('d F Y') }}</p>
        </div>

        <div class="rounded-2xl border p-5" style="background:var(--card); border-color:var(--line)">
            <h3 class="font-display font-semibold mb-4 text-sm" style="color:var(--ink)">Statistik Belajar</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm" style="color:var(--muted)">Total XP</span>
                    <span class="font-display font-bold text-lg" style="color:var(--soga)">{{ $totalXp }} XP</span>
                </div>
                <div>
                    <div class="h-1.5 rounded-full overflow-hidden" style="background:var(--line)">
                        <div class="h-full rounded-full" style="width: {{ min(100, ($totalXp / 500) * 100) }}%; background:linear-gradient(90deg, var(--wedel), var(--gold))"></div>
                    </div>
                    <p class="text-xs mt-1" style="color:var(--muted)">{{ $totalXp }} / 500 XP</p>
                </div>
                <div class="flex items-center justify-between pt-2 border-t" style="border-color:var(--line)">
                    <span class="text-sm" style="color:var(--muted)">Level Selesai</span>
                    <span class="font-semibold" style="color:var(--ink)">{{ $completedCount }} / 5</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm" style="color:var(--muted)">Lencana Diraih</span>
                    <span class="font-semibold" style="color:var(--ink)">{{ count($earnedBadges) }} / 5</span>
                </div>
                @if($user->certificate_available ?? false)
                <div class="pt-2 border-t" style="border-color:var(--line)">
                    <a href="{{ route('certificate') }}"
                       class="flex items-center justify-center gap-2 w-full py-2 text-sm font-medium
                              rounded-full border transition hover:opacity-80"
                       style="color:var(--ink); background:var(--gold); border-color:#B08B3A">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955
                                     0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622
                                     5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Unduh Sertifikat
                    </a>
                </div>
                @endif
            </div>
        </div>

        <div class="rounded-2xl border p-5" style="background:var(--card); border-color:var(--line)">
            <h3 class="font-display font-semibold mb-4 text-sm" style="color:var(--ink)">Koleksi Lencana</h3>
            <div class="grid grid-cols-5 gap-2">
                @foreach($allBadges as $badge)
                @php $earned = $earnedBadges->contains('id', $badge->id); @endphp
                <div class="flex flex-col items-center gap-1" title="{{ $badge->name }}{{ $earned ? '' : ' (belum diraih)' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl border"
                         style="{{ $earned ? 'background:#FBF3DF; border-color:#EAD9AE; border-width:2px' : 'background:var(--cream); border-color:var(--line); filter:grayscale(1); opacity:.4' }}">
                        🏅
                    </div>
                    <span class="text-xs text-center leading-tight line-clamp-2" style="color:var(--muted)">
                        {{ explode(' ', $badge->name)[0] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="rounded-2xl border p-5 sm:p-6" style="background:var(--card); border-color:var(--line)">
            <h3 class="font-display font-semibold mb-5 text-sm" style="color:var(--ink)">Histori Progres Per Level</h3>
            <div class="space-y-4">
                @foreach($progressList as $item)
                @php
                    $statusStyle = match($item->status) {
                        'completed' => 'color:#0F7A54; background:#E7F6EF; border-color:#BFE6D5',
                        'unlocked'  => 'color:var(--wedel); background:#EAF0F6; border-color:#C9D9E8',
                        default     => 'color:var(--muted); background:var(--cream); border-color:var(--line)',
                    };
                    $statusLabel = match($item->status) {
                        'completed' => 'Selesai',
                        'unlocked'  => 'Aktif',
                        default     => 'Terkunci',
                    };
                @endphp
                <div class="flex items-start gap-4 p-4 rounded-xl border transition" style="border-color:var(--line)">
                    <div class="w-10 h-10 flex-shrink-0 rounded-full flex items-center justify-center font-display font-bold text-sm"
                         style="background:var(--cream); color:var(--muted)">
                        {{ $item->level->level_number ?? '?' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <p class="font-medium text-sm" style="color:var(--ink)">
                                {{ $item->level->name ?? 'Level tidak ditemukan' }}
                            </p>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full border" style="{{ $statusStyle }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        @if($item->score !== null)
                        <div class="mt-2">
                            <div class="flex items-center justify-between text-xs mb-1" style="color:var(--muted)">
                                <span>Skor: <span class="font-semibold {{ $item->score >= 75 ? 'text-emerald-600' : 'text-red-500' }}">{{ $item->score }}%</span></span>
                                @if($item->xp_earned > 0)
                                <span class="font-semibold" style="color:var(--soga)">+{{ $item->xp_earned }} XP</span>
                                @endif
                            </div>
                            <div class="h-1.5 rounded-full overflow-hidden" style="background:var(--line)">
                                <div class="h-full rounded-full {{ $item->score >= 75 ? 'bg-emerald-400' : 'bg-red-300' }}"
                                     style="width: {{ $item->score }}%"></div>
                            </div>
                        </div>
                        @else
                        <p class="text-xs mt-1" style="color:var(--muted)">Belum mengerjakan kuis</p>
                        @endif
                        @if($item->attempts > 0)
                        <p class="text-xs mt-1" style="color:var(--muted)">{{ $item->attempts }}× percobaan</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
