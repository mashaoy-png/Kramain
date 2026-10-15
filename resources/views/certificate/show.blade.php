@extends('layouts.app')

@section('title', 'Sertifikat Penyelesaian')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-6 text-center">
        <h1 class="font-display font-bold text-2xl" style="color:var(--ink)">Sertifikat Penyelesaian</h1>
        <p class="mt-1" style="color:var(--muted)">
            Selamat! Kamu telah menyelesaikan seluruh level pembelajaran unggah-ungguh bahasa Jawa.
        </p>
    </div>

    <div id="certificate-card"
         class="rounded-2xl p-8 sm:p-12 mb-6 shadow-lg relative overflow-hidden border-4 border-double"
         style="background:var(--card); border-color:#EAD9AE">

        <div class="absolute top-3 left-3 w-12 h-12 border-t-4 border-l-4 rounded-tl-lg opacity-40" style="border-color:var(--soga)"></div>
        <div class="absolute top-3 right-3 w-12 h-12 border-t-4 border-r-4 rounded-tr-lg opacity-40" style="border-color:var(--soga)"></div>
        <div class="absolute bottom-3 left-3 w-12 h-12 border-b-4 border-l-4 rounded-bl-lg opacity-40" style="border-color:var(--soga)"></div>
        <div class="absolute bottom-3 right-3 w-12 h-12 border-b-4 border-r-4 rounded-br-lg opacity-40" style="border-color:var(--soga)"></div>

        <div class="text-center relative z-10">

            <div class="flex justify-center mb-4">
                <svg class="w-14 h-14" style="color:var(--soga)" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.6"/>
                    <circle cx="24" cy="24" r="16" stroke="currentColor" stroke-width="0.9"/>
                    <g stroke="currentColor" stroke-width="1.2">
                        <ellipse cx="24" cy="15" rx="3.8" ry="6.8"/>
                        <ellipse cx="24" cy="33" rx="3.8" ry="6.8"/>
                        <ellipse cx="15" cy="24" rx="6.8" ry="3.8"/>
                        <ellipse cx="33" cy="24" rx="6.8" ry="3.8"/>
                    </g>
                    <circle cx="24" cy="24" r="2.6" fill="currentColor"/>
                </svg>
            </div>
            <p class="font-mono text-xs uppercase tracking-widest mb-1" style="color:var(--soga)">Kramain</p>
            <p class="text-xs mb-6" style="color:var(--muted)">Media Pembelajaran Unggah-Ungguh Bahasa Jawa</p>

            <p class="text-sm mb-1" style="color:var(--muted)">Dengan bangga memberikan</p>
            <h2 class="font-display font-bold text-2xl sm:text-3xl mb-6 leading-tight" style="color:var(--ink)">
                Sertifikat Penyelesaian
            </h2>

            <div class="flex items-center gap-3 mb-6">
                <div class="flex-1 h-px" style="background:linear-gradient(to right, transparent, #EAD9AE)"></div>
                <span class="text-lg" style="color:var(--gold)">✦</span>
                <div class="flex-1 h-px" style="background:linear-gradient(to left, transparent, #EAD9AE)"></div>
            </div>

            <p class="text-sm mb-1" style="color:var(--muted)">Diberikan kepada</p>
            <p class="text-3xl sm:text-4xl font-display font-bold mb-2 tracking-wide" style="color:var(--soga)">
                {{ auth()->user()->name }}
            </p>

            <div class="w-24 h-0.5 mx-auto mb-6" style="background:#EAD9AE"></div>

            <p class="text-sm leading-relaxed max-w-md mx-auto mb-6" style="color:var(--ink)">
                Telah berhasil menyelesaikan seluruh program pembelajaran
                <span class="font-semibold">Unggah-Ungguh Bahasa Jawa</span>
                mencakup tingkatan <span class="font-medium">Ngoko Lugu, Ngoko Alus, Krama Lugu,
                Krama Alus</span>, dan Evaluasi Komprehensif dengan skor minimal 75% pada setiap level.
            </p>

            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="text-center">
                    <p class="text-2xl font-display font-bold" style="color:var(--soga)">{{ auth()->user()->totalXp() }}</p>
                    <p class="text-xs mt-0.5" style="color:var(--muted)">Total XP</p>
                </div>
                <div class="text-center border-x" style="border-color:var(--line)">
                    <p class="text-2xl font-display font-bold" style="color:var(--wedel)">5</p>
                    <p class="text-xs mt-0.5" style="color:var(--muted)">Level Selesai</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-display font-bold text-emerald-600">5</p>
                    <p class="text-xs mt-0.5" style="color:var(--muted)">Lencana Diraih</p>
                </div>
            </div>

            <div class="flex items-end justify-between flex-wrap gap-4">
                <div class="text-left">
                    <p class="text-xs" style="color:var(--muted)">Diterbitkan pada</p>
                    <p class="text-sm font-semibold" style="color:var(--ink)">
                        {{ now()->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="w-24 h-0.5 mb-1 ml-auto" style="background:var(--line)"></div>
                    <p class="text-xs" style="color:var(--muted)">Program Studi S1 Informatika</p>
                    <p class="text-xs" style="color:var(--muted)">Universitas Ahmad Dahlan</p>
                    <p class="text-xs" style="color:var(--muted)">Yogyakarta, 2025/2026</p>
                </div>
            </div>

            <div class="absolute -bottom-2 left-1/2 -translate-x-1/2">
                <div class="w-16 h-16 rounded-full border-4 flex items-center justify-center opacity-20 text-2xl"
                     style="border-color:#EAD9AE; background:#FBF3DF">🏅</div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3">
        <button onclick="window.print()"
                class="flex-1 flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold
                       text-white rounded-full transition hover:opacity-90" style="background:var(--wedel)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Unduh / Cetak Sertifikat
        </button>
        <a href="{{ route('dashboard') }}"
           class="flex-1 flex items-center justify-center gap-2 px-5 py-3 text-sm font-medium
                  rounded-full border transition hover:opacity-80" style="color:var(--soga); border-color:var(--soga)">
            Kembali ke Dashboard
        </a>
    </div>

    <p class="text-center text-xs mt-4" style="color:var(--muted)">
        Gunakan fungsi Cetak browser (Ctrl+P / Cmd+P) dan pilih "Simpan sebagai PDF" untuk menyimpan sertifikat.
    </p>

</div>

@endsection

@push('scripts')
<style>
@media print {
    header, nav, .no-print, footer { display: none !important; }
    #certificate-card {
        border: 4px double #EAD9AE !important;
        box-shadow: none !important;
        margin: 0 !important;
        page-break-inside: avoid;
    }
    body { background: white !important; }
}
</style>
@endpush
