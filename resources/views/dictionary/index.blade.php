@extends('layouts.app')

@section('title', 'Kamus Kosa Kata Jawa — Translator Indonesia - Jawa')

@section('content')
<script>
window.dictionaryManager = function() {
    return {
        q: {{ Js::from($q) }},
        category: {{ Js::from($category) }},
        words: {{ Js::from($words->items()) }},
        total: {{ (int) $words->total() }},
        currentPage: {{ (int) $words->currentPage() }},
        lastPage: {{ (int) $words->lastPage() }},
        firstItem: {{ (int) ($words->firstItem() ?? 0) }},
        lastItem: {{ (int) ($words->lastItem() ?? 0) }},
        hasPages: {{ $words->hasPages() ? 'true' : 'false' }},
        isLoading: false,
        debounceTimer: null,
        abortController: null,
        copiedText: '', 
        showToast: false,

        copy(text, label) {
            if (!text || text === '—') return;
            navigator.clipboard.writeText(text);
            this.copiedText = label + ': ' + text;
            this.showToast = true;
            setTimeout(() => { this.showToast = false; }, 2200);
        },

        // Debounced input handler (300ms) untuk kueri real-time langsung ke database
        onSearchInput(e) {
            this.q = e.target.value;
            clearTimeout(this.debounceTimer);
            this.isLoading = true;
            this.debounceTimer = setTimeout(() => {
                this.fetchResults(1);
            }, 300);
        },

        // Pencarian langsung (Enter, tombol Cari, filter kategori, atau chip saran)
        searchImmediate(keyword = null) {
            clearTimeout(this.debounceTimer);
            if (keyword !== null) {
                this.q = keyword;
            }
            this.fetchResults(1);
        },

        onCategoryChange(e) {
            this.category = e.target.value;
            this.searchImmediate();
        },

        clearSearch() {
            this.q = '';
            this.searchImmediate();
            this.$nextTick(() => {
                this.$refs.searchInput?.focus();
            });
        },

        resetAll() {
            this.q = '';
            this.category = 'all';
            this.searchImmediate();
            this.$nextTick(() => {
                this.$refs.searchInput?.focus();
            });
        },

        // Fungsi fetch real-time ke backend dengan abort controller untuk mencegah race conditions
        async fetchResults(page = 1) {
            if (this.abortController) {
                this.abortController.abort();
            }
            this.abortController = new AbortController();
            this.isLoading = true;

            const params = new URLSearchParams();
            if (this.q.trim()) {
                params.set('q', this.q.trim());
            }
            if (this.category && this.category !== 'all') {
                params.set('category', this.category);
            }
            if (page > 1) {
                params.set('page', page);
            }

            const queryString = params.toString();
            const fetchUrl = '{{ route('dictionary.index') }}' + (queryString ? '?' + queryString : '');

            // Sinkronisasi parameter URL tanpa refresh halaman
            const newUrl = queryString ? '{{ route('dictionary.index') }}?' + queryString : '{{ route('dictionary.index') }}';
            window.history.replaceState({ q: this.q, category: this.category, page }, '', newUrl);

            try {
                const response = await fetch(fetchUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    signal: this.abortController.signal
                });

                if (!response.ok) {
                    throw new Error('Gagal memuat data dari database');
                }

                const data = await response.json();
                if (data.success) {
                    this.words = data.data || [];
                    this.total = data.total || 0;
                    this.currentPage = data.current_page || 1;
                    this.lastPage = data.last_page || 1;
                    this.firstItem = data.first_item || (this.words.length ? 1 : 0);
                    this.lastItem = data.last_item || this.words.length;
                    this.hasPages = (data.last_page > 1);
                }
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error('Error saat kueri kamus:', error);
                }
            } finally {
                this.isLoading = false;
            }
        },

        // Helper highlight teks yang cocok dengan kata kunci pencarian
        highlight(text) {
            if (!text) return '—';
            const str = String(text);
            if (!this.q || !this.q.trim()) {
                return this.escapeHtml(str);
            }
            const escapedQuery = this.q.trim().replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const escapedStr = this.escapeHtml(str);
            try {
                const regex = new RegExp(`(${escapedQuery})`, 'gi');
                return escapedStr.replace(regex, '<mark class="bg-amber-200/90 text-amber-950 font-bold px-1 py-0.5 rounded shadow-2xs">$1</mark>');
            } catch (e) {
                return escapedStr;
            }
        },

        escapeHtml(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    };
};
</script>

<div x-data="dictionaryManager()" x-cloak>

    {{-- ===== HEADER ===== --}}
    <div class="mb-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
            <span class="inline-flex items-center gap-1.5 font-mono text-[11px] uppercase tracking-widest px-3 py-1 rounded-full border"
                  style="color:var(--soga); border-color:var(--line); background:var(--card)">
                <span class="w-1.5 h-1.5 rounded-full" style="background:var(--soga)"></span>
                Kamus Unggah-Ungguh Jawa
            </span>

            <span class="text-xs font-mono px-2.5 py-1 rounded-full border"
                  style="background:#FBF3DF; border-color:#EAD9AE; color:var(--soga)">
                {{ number_format($totalWords, 0, ',', '.') }} Kosa Kata Terdaftar
            </span>
        </div>

        <h1 class="font-display font-bold text-2.5xl sm:text-3xl tracking-tight mb-2" style="color:var(--ink)">
            Cari Kosa Kata & Penerjemah
        </h1>
        <p class="text-sm sm:text-base max-w-2xl leading-relaxed" style="color:var(--muted)">
            Cari kata dalam bahasa Indonesia atau Jawa untuk melihat tingkatan ragam bahasa: 
            <strong class="font-semibold text-[var(--wedel)]">Ngoko</strong>, 
            <strong class="font-semibold text-[var(--soga)]">Krama Alus</strong>, dan 
            <strong class="font-semibold text-[var(--gold)]">Krama Inggil</strong>.
        </p>
    </div>

    {{-- ===== TINGKATAN BAHASA EXPLAINER CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
        <div class="rounded-2xl p-4 border" style="background:var(--card); border-color:#C9D9E8">
            <div class="flex items-center gap-2 mb-1.5">
                <span class="w-2.5 h-2.5 rounded-full" style="background:#1E3A5F"></span>
                <span class="font-display font-bold text-sm" style="color:#1E3A5F">Ngoko</span>
            </div>
            <p class="text-xs leading-relaxed" style="color:var(--muted)">
                Digunakan untuk berbicara dengan teman sebaya, akrab, atau orang yang lebih muda.
            </p>
        </div>

        <div class="rounded-2xl p-4 border" style="background:var(--card); border-color:#C9B8A8">
            <div class="flex items-center gap-2 mb-1.5">
                <span class="w-2.5 h-2.5 rounded-full" style="background:#8B4226"></span>
                <span class="font-display font-bold text-sm" style="color:#8B4226">Krama Alus / Lugu</span>
            </div>
            <p class="text-xs leading-relaxed" style="color:var(--muted)">
                Bentuk sopan untuk situasi formal atau menghormati orang yang belum terlalu akrab.
            </p>
        </div>

        <div class="rounded-2xl p-4 border" style="background:var(--card); border-color:#EAD9AE">
            <div class="flex items-center gap-2 mb-1.5">
                <span class="w-2.5 h-2.5 rounded-full" style="background:#C9A24B"></span>
                <span class="font-display font-bold text-sm" style="color:#8B4226">Krama Inggil</span>
            </div>
            <p class="text-xs leading-relaxed" style="color:var(--muted)">
                Bentuk penghormatan tertinggi untuk orang tua, guru, sesepuh, atau tokoh yang dihormati.
            </p>
        </div>
    </div>

    {{-- ===== FORM PENCARIAN (SEARCH BAR REAL-TIME DENGAN DEBOUNCING) ===== --}}
    <div class="rounded-2xl border p-5 sm:p-6 mb-6 shadow-sm" style="background:var(--card); border-color:var(--line)">
        <form @submit.prevent="searchImmediate()" action="{{ route('dictionary.index') }}" method="GET">
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Input Pencarian --}}
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" style="color:var(--muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <input type="text" 
                           x-ref="searchInput"
                           name="q" 
                           :value="q"
                           @input="onSearchInput($event)"
                           placeholder="Ketik huruf/kata (misal: m, mak, mangan, tidur, tindak)..." 
                           autofocus
                           autocomplete="off"
                           class="w-full pl-11 pr-11 py-3 rounded-xl border text-sm sm:text-base focus:outline-none transition"
                           style="background:#FFFDF8; border-color:var(--line); color:var(--ink);"
                           onfocus="this.style.borderColor='var(--wedel)'; this.style.boxShadow='0 0 0 2px rgba(30,58,95,0.15)'"
                           onblur="this.style.borderColor='var(--line)'; this.style.boxShadow='none'">

                    {{-- Loading Spinner saat Request Backend Sedang Berlangsung --}}
                    <div x-show="isLoading" 
                         class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none"
                         style="display:none;">
                        <svg class="animate-spin w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    {{-- Tombol Hapus / Clear saat ada teks dan tidak loading --}}
                    <button type="button" 
                            x-show="q.trim() !== '' && !isLoading" 
                            @click="clearSearch()" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition cursor-pointer"
                            title="Hapus kata kunci"
                            style="display:none;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Filter Kategori --}}
                <div class="sm:w-48">
                    <select name="category" 
                            :value="category"
                            @change="onCategoryChange($event)"
                            class="w-full py-3 px-3.5 rounded-xl border text-sm focus:outline-none transition cursor-pointer"
                            style="background:#FFFDF8; border-color:var(--line); color:var(--ink);">
                        <option value="all">Semua Bahasa</option>
                        <option value="indonesia">Indonesia</option>
                        <option value="ngoko">Ngoko</option>
                        <option value="krama_alus">Krama Alus</option>
                        <option value="krama_inggil">Krama Inggil</option>
                    </select>
                </div>

                {{-- Tombol Cari --}}
                <button type="submit" 
                        class="px-6 py-3 rounded-xl font-semibold text-sm text-white transition flex items-center justify-center gap-2 hover:opacity-90 shadow-sm cursor-pointer"
                        style="background:var(--wedel)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Cari</span>
                </button>
            </div>

            {{-- Quick Keyword Chips & Debounce Live Indicator --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-3 border-t text-xs" style="border-color:var(--line)">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-mono uppercase tracking-wider" style="color:var(--muted)">Saran:</span>
                    @foreach($quickKeywords as $keyword)
                    <button type="button" 
                            @click="searchImmediate('{{ $keyword }}')"
                            :style="q.toLowerCase() === '{{ strtolower($keyword) }}' 
                                ? 'background:var(--wedel); color:#fff; border-color:var(--wedel)' 
                                : 'background:var(--cream); color:var(--ink); border-color:var(--line)'"
                            class="px-2.5 py-1 text-xs rounded-full border transition hover:scale-105 cursor-pointer">
                        {{ $keyword }}
                    </button>
                    @endforeach
                </div>
            </div>
        </form>
    </div>

    {{-- ===== HASIL PENCARIAN ===== --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="font-display font-semibold text-lg" style="color:var(--ink)">
                <template x-if="q.trim() !== ''">
                    <span>Hasil Pencarian: <span class="italic font-normal" style="color:var(--soga)" x-text="'&ldquo;' + q + '&rdquo;'"></span></span>
                </template>
                <template x-if="q.trim() === ''">
                    <span>Daftar Kosa Kata</span>
                </template>
            </span>
            <span class="text-xs px-2.5 py-0.5 rounded-full font-mono border"
                  style="background:#FBF3DF; border-color:#EAD9AE; color:var(--soga)"
                  x-text="total + ' kata'">
            </span>
            <span x-show="isLoading" class="inline-flex items-center gap-1.5 text-xs font-mono text-amber-700 animate-pulse ml-1" style="display:none;">
                <svg class="animate-spin w-3.5 h-3.5 text-amber-700" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memuat...</span>
            </span>
        </div>

        <button type="button" 
                x-show="q.trim() !== '' || category !== 'all'"
                @click="resetAll()" 
                class="text-xs font-medium inline-flex items-center gap-1 hover:underline transition cursor-pointer"
                style="color:var(--soga); display:none;">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Reset filter pencarian
        </button>
    </div>

    {{-- JIKA HASIL KOSONG --}}
    <div x-show="words.length === 0 && !isLoading" 
         class="rounded-2xl border p-8 sm:p-12 text-center" 
         style="background:var(--card); border-color:var(--line); display:none;">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 border"
             style="background:var(--cream); border-color:var(--line); color:var(--soga)">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="font-display font-bold text-xl mb-2" style="color:var(--ink)">
            Kosa Kata Tidak Ditemukan
        </h3>
        <p class="text-sm max-w-md mx-auto mb-6 leading-relaxed" style="color:var(--muted)">
            Kata <strong class="text-[var(--soga)]" x-text="'&ldquo;' + q + '&rdquo;'"></strong> belum ada di dalam basis data kamus kami. Coba periksa ejaan kata atau gunakan bentuk kata dasarnya.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <button type="button"
                    @click="resetAll()" 
                    class="px-5 py-2.5 rounded-xl font-semibold text-sm text-white transition hover:opacity-90 cursor-pointer"
                    style="background:var(--wedel)">
                Lihat Semua Kosa Kata
            </button>
        </div>
    </div>

    {{-- ===== TAMPILAN DESKTOP (TABEL) ===== --}}
    <div x-show="words.length > 0" 
         :class="{ 'opacity-60': isLoading }"
         class="hidden md:block rounded-2xl border overflow-hidden shadow-sm mb-6 transition-opacity duration-200" 
         style="background:var(--card); border-color:var(--line)">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b text-xs font-mono uppercase tracking-wider" 
                        style="background:rgba(247,238,221,0.6); border-color:var(--line); color:var(--soga)">
                        <th class="py-3.5 px-4 font-semibold">Indonesia</th>
                        <th class="py-3.5 px-4 font-semibold">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full" style="background:#1E3A5F"></span>
                                Ngoko
                            </span>
                        </th>
                        <th class="py-3.5 px-4 font-semibold">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full" style="background:#8B4226"></span>
                                Krama Alus
                            </span>
                        </th>
                        <th class="py-3.5 px-4 font-semibold">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full" style="background:#C9A24B"></span>
                                Krama Inggil
                            </span>
                        </th>
                        <th class="py-3.5 px-4 font-semibold text-right">Salin</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--line)">
                    <template x-for="item in words" :key="item.id">
                        <tr class="hover:bg-[#FBF8F1] transition group">
                            {{-- Indonesia --}}
                            <td class="py-3.5 px-4 text-sm font-semibold" style="color:var(--ink)">
                                <span x-html="highlight(item.indonesia)"></span>
                            </td>

                            {{-- Ngoko --}}
                            <td class="py-3.5 px-4 text-sm font-medium">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-xs sm:text-sm font-mono"
                                     style="background:#E7EEF5; color:#1E3A5F; border-color:#C9D9E8">
                                    <span x-html="highlight(item.ngoko)"></span>
                                </div>
                            </td>

                            {{-- Krama Alus --}}
                            <td class="py-3.5 px-4 text-sm font-medium">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-xs sm:text-sm font-mono"
                                     style="background:#DCCFC2; color:#5A3A2A; border-color:#C9B8A8">
                                    <span x-html="highlight(item.krama_alus)"></span>
                                </div>
                            </td>

                            {{-- Krama Inggil --}}
                            <td class="py-3.5 px-4 text-sm font-medium">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-xs sm:text-sm font-mono font-semibold"
                                     style="background:#FBF3DF; color:#8B4226; border-color:#EAD9AE">
                                    <span x-html="highlight(item.krama_inggil)"></span>
                                </div>
                            </td>

                            {{-- Aksi Salin --}}
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" 
                                            @click="copy(item.krama_inggil || item.krama_alus || item.ngoko || item.indonesia, 'Krama')"
                                            class="p-1.5 rounded-lg border text-xs hover:bg-[var(--cream)] transition opacity-70 group-hover:opacity-100 cursor-pointer"
                                            style="border-color:var(--line); color:var(--muted)"
                                            title="Salin Krama">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== TAMPILAN MOBILE (KARTU RESPONSIVE) ===== --}}
    <div x-show="words.length > 0" 
         :class="{ 'opacity-60': isLoading }"
         class="md:hidden space-y-3 mb-6 transition-opacity duration-200">
        <template x-for="item in words" :key="item.id">
            <div class="rounded-2xl border p-4 shadow-sm" style="background:var(--card); border-color:var(--line)">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <div>
                        <span class="text-[10px] font-mono uppercase tracking-wider" style="color:var(--muted)">Bahasa Indonesia</span>
                        <h3 class="font-display font-bold text-lg leading-tight" style="color:var(--ink)">
                            <span x-html="highlight(item.indonesia)"></span>
                        </h3>
                    </div>
                    <button type="button" 
                            @click="copy(item.indonesia + ': Ngoko (' + (item.ngoko || '—') + '), Krama (' + (item.krama_alus || '—') + '), Inggil (' + (item.krama_inggil || '—') + ')', 'Kamus')"
                            class="p-2 rounded-xl border text-xs hover:bg-[var(--cream)] transition flex-shrink-0 cursor-pointer"
                            style="border-color:var(--line); color:var(--muted)"
                            title="Salin semua tingkatan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-2 pt-2 border-t" style="border-color:var(--line)">
                    {{-- Ngoko --}}
                    <div class="flex items-center justify-between p-2.5 rounded-xl border"
                         style="background:#E7EEF5; border-color:#C9D9E8">
                        <span class="text-xs font-semibold" style="color:#1E3A5F">Ngoko</span>
                        <span class="font-mono font-medium text-sm" style="color:#1E3A5F" x-html="highlight(item.ngoko)"></span>
                    </div>

                    {{-- Krama Alus --}}
                    <div class="flex items-center justify-between p-2.5 rounded-xl border"
                         style="background:#DCCFC2; border-color:#C9B8A8">
                        <span class="text-xs font-semibold" style="color:#5A3A2A">Krama Alus</span>
                        <span class="font-mono font-medium text-sm" style="color:#5A3A2A" x-html="highlight(item.krama_alus)"></span>
                    </div>

                    {{-- Krama Inggil --}}
                    <div class="flex items-center justify-between p-2.5 rounded-xl border"
                         style="background:#FBF3DF; border-color:#EAD9AE">
                        <span class="text-xs font-semibold" style="color:#8B4226">Krama Inggil</span>
                        <span class="font-mono font-bold text-sm" style="color:#8B4226" x-html="highlight(item.krama_inggil)"></span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- ===== PAGINASI REAL-TIME ===== --}}
    <div x-show="hasPages && words.length > 0" 
         class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-4 border-t" 
         style="border-color:var(--line)">
        <div class="text-xs font-mono" style="color:var(--muted)">
            Menampilkan <span x-text="firstItem"></span> – <span x-text="lastItem"></span> dari <span x-text="total"></span> kosa kata
        </div>

        <div class="flex items-center gap-1.5">
            {{-- Tombol Sebelumnya --}}
            <button type="button" 
                    @click="fetchResults(currentPage - 1)"
                    :disabled="currentPage <= 1 || isLoading"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage <= 1, 'hover:bg-[var(--cream)] cursor-pointer': currentPage > 1 }"
                    class="px-3 py-1.5 text-xs rounded-xl border transition"
                    style="background:var(--card); border-color:var(--line); color:var(--ink)">
                ← Sebelumnya
            </button>

            {{-- Info Halaman Aktif --}}
            <span class="px-3 py-1.5 text-xs rounded-xl border font-mono font-medium"
                  style="background:#FBF3DF; border-color:#EAD9AE; color:var(--soga)">
                Hal <span x-text="currentPage"></span> / <span x-text="lastPage"></span>
            </span>

            {{-- Tombol Selanjutnya --}}
            <button type="button" 
                    @click="fetchResults(currentPage + 1)"
                    :disabled="currentPage >= lastPage || isLoading"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage >= lastPage, 'hover:bg-[var(--cream)] cursor-pointer': currentPage < lastPage }"
                    class="px-3 py-1.5 text-xs rounded-xl border transition"
                    style="background:var(--card); border-color:var(--line); color:var(--ink)">
                Selanjutnya →
            </button>
        </div>
    </div>

    {{-- ===== TOAST SALIN NOTIFIKASI ===== --}}
    <div x-show="showToast"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-3"
         class="fixed bottom-20 md:bottom-8 right-6 z-50 rounded-2xl border px-4 py-3 shadow-lg flex items-center gap-2.5 text-sm"
         style="background:var(--wedel); border-color:var(--wedel-deep); color:#fff; display:none;">
        <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <span x-text="copiedText"></span>
    </div>

</div>
@endsection
