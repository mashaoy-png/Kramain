<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kramain — Kuasai Unggah-Ungguh Bahasa Jawa & Kamus Lengkap</title>
    <meta name="description" content="Media pembelajaran unggah-ungguh bahasa Jawa berbasis gamifikasi dan kamus kosa kata lengkap (Ngoko, Krama Alus, Krama Inggil) untuk siswa dan masyarakat.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

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
        [x-cloak] { display: none !important; }

        .batik-bg{
            background-image: radial-gradient(circle at 1px 1px, rgba(139,66,38,0.10) 1px, transparent 0);
            background-size: 22px 22px;
        }

        .card-fan{ position:relative; height:220px; }
        .lv{
            position:absolute; left:50%; top:8px; width:120px; height:170px;
            border-radius:14px; border:1px solid var(--line); padding:14px;
            display:flex; flex-direction:column; justify-content:space-between;
            box-shadow:0 12px 26px -14px rgba(36,26,18,0.35);
            transition:transform .3s ease, box-shadow .3s ease;
        }
        .lv:hover{ box-shadow:0 16px 30px -12px rgba(36,26,18,0.4); }
        .lv1{ transform:translateX(-50%) rotate(-16deg) translateY(22px); z-index:1; background:#E7EEF5; border-color:#C9D9E8; color:#1E3A5F; }
        .lv2{ transform:translateX(-50%) rotate(-8deg)  translateY(8px);  z-index:2; background:#DDE1E7; border-color:#C4CCD3; color:#2B3A4A; }
        .lv3{ transform:translateX(-50%) rotate(0deg)   translateY(-8px);z-index:3; background:#DCCFC2; border-color:#C9B8A8; color:#5A3A2A; }
        .lv4{ transform:translateX(-50%) rotate(8deg)   translateY(8px); z-index:2; background:#D8B896; border-color:#C7A276; color:#4A2E1A; }
        .lv5{ transform:translateX(-50%) rotate(16deg)  translateY(22px);z-index:1; background:var(--gold); border-color:#B08B3A; color:#241A12; }
        .lv1:hover{ transform:translateX(-50%) rotate(-16deg) translateY(12px); }
        .lv2:hover{ transform:translateX(-50%) rotate(-8deg)  translateY(-2px); }
        .lv3:hover{ transform:translateX(-50%) rotate(0deg)   translateY(-18px); }
        .lv4:hover{ transform:translateX(-50%) rotate(8deg)   translateY(-2px); }
        .lv5:hover{ transform:translateX(-50%) rotate(16deg)  translateY(12px); }

        @media (prefers-reduced-motion: no-preference){
            .fade-up{ animation:fadeUp .7s ease both; }
            .fade-up-d1{ animation-delay:.1s; }
            .fade-up-d2{ animation-delay:.18s; }
            @keyframes fadeUp{ from{ opacity:0; transform:translateY(14px); } to{ opacity:1; transform:translateY(0); } }
        }
        @media (prefers-reduced-motion: reduce){
            .lv, .lv:hover{ transition:none; }
        }
    </style>

    <script>
    window.cardDeck = function() {
        return {
            cards: [
                { id: 1, level: 'LEVEL  1', aksara: 'ꦤ', title: 'Ngoko Lugu', xp: '+50  XP', bg: '#E7EEF5', border: '#CADAE9', text: '#1E3A5F' },
                { id: 2, level: 'LEVEL  2', aksara: 'ꦭ', title: 'Ngoko Alus', xp: '+75  XP', bg: '#DDE1E7', border: '#C5CDD5', text: '#2B3A4A' },
                { id: 3, level: 'LEVEL  3', aksara: 'ꦟ', title: 'Krama Lugu', xp: '+100  XP', bg: '#DCCFC2', border: '#C9B9AA', text: '#5A3A2A' },
                { id: 4, level: 'LEVEL  4', aksara: 'ꦫ', title: 'Krama Alus', xp: '+125  XP', bg: '#D8B896', border: '#C8A378', text: '#4A2E1A' },
                { id: 5, level: 'LEVEL  5', aksara: 'ꦩ', title: 'Evaluasi', xp: '+150  XP', bg: '#C9A24B', border: '#B08B3A', text: '#241A12' }
            ],
            isAnimating: false,
            swipingCardId: null,
            swipeDirection: 1,
            currentSwipeDir: 1,

            nextCard() {
                if (this.isAnimating) return;
                this.isAnimating = true;

                const topCard = this.cards[0];
                this.swipingCardId = topCard.id;
                this.currentSwipeDir = this.swipeDirection;
                this.swipeDirection *= -1; // Bergantian arah lempar (kanan lalu kiri)

                setTimeout(() => {
                    const popped = this.cards.shift();
                    this.cards.push(popped);
                    this.swipingCardId = null;

                    setTimeout(() => {
                        this.isAnimating = false;
                    }, 40);
                }, 300);
            },

            getCardStyle(card, index) {
                if (this.swipingCardId === card.id) {
                    const xOffset = this.currentSwipeDir * 230;
                    const rot = this.currentSwipeDir * 28;
                    return `
                        z-index: 20;
                        transform: translate(-50%, -50%) translate3d(${xOffset}px, -45px, 0) rotate(${rot}deg) scale(1.06);
                        opacity: 0;
                        pointer-events: none;
                        transition: transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.26s ease;
                    `;
                }

                // Penataan tumpukan kartu: kartu teratas tegak sempurna, kartu bawahnya miring bergantian
                const configs = [
                    { rot: 0,   x: 0,   y: 0,  scale: 1,    z: 5, op: 1 },
                    { rot: 5,   x: 6,   y: 6,  scale: 0.96, z: 4, op: 0.96 },
                    { rot: -6,  x: -6,  y: 12, scale: 0.92, z: 3, op: 0.90 },
                    { rot: 9,   x: 9,   y: 18, scale: 0.88, z: 2, op: 0.84 },
                    { rot: -11, x: -9,  y: 24, scale: 0.84, z: 1, op: 0.76 },
                ];

                const conf = configs[index] || configs[configs.length - 1];
                return `
                    z-index: ${conf.z};
                    transform: translate(-50%, -50%) translate3d(${conf.x}px, ${conf.y}px, 0) rotate(${conf.rot}deg) scale(${conf.scale});
                    opacity: ${conf.op};
                    transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
                `;
            }
        };
    };

    window.dictionaryManager = function() {
        return {
            q: {{ Js::from($q ?? '') }},
            category: {{ Js::from($category ?? 'all') }},
            words: {{ Js::from(isset($words) ? $words->items() : []) }},
            total: {{ isset($words) ? (int) $words->total() : 0 }},
            isLoading: false,
            debounceTimer: null,
            abortController: null,
            copiedText: '', 
            showToast: false,

            init() {
                if (this.q && this.q.trim() !== '') {
                    this.$nextTick(() => {
                        document.getElementById('kamus')?.scrollIntoView({ behavior: 'smooth' });
                    });
                }
            },

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
                if (!this.q.trim()) {
                    this.words = [];
                    this.total = 0;
                    this.isLoading = false;
                    window.history.replaceState({}, '', '{{ route('welcome') }}#kamus');
                    return;
                }
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
                if (!this.q.trim()) {
                    this.words = [];
                    this.total = 0;
                    this.isLoading = false;
                    window.history.replaceState({}, '', '{{ route('welcome') }}#kamus');
                    return;
                }
                this.fetchResults(1);
            },

            onCategoryChange(e) {
                this.category = e.target.value;
                if (this.q.trim()) {
                    this.searchImmediate();
                }
            },

            clearSearch() {
                this.q = '';
                this.words = [];
                this.total = 0;
                this.isLoading = false;
                window.history.replaceState({}, '', '{{ route('welcome') }}#kamus');
                this.$nextTick(() => {
                    this.$refs.searchInput?.focus();
                });
            },

            // Fungsi fetch real-time ke backend dengan abort controller untuk mencegah race conditions
            async fetchResults(page = 1, shouldScroll = false) {
                if (!this.q.trim()) {
                    this.words = [];
                    this.total = 0;
                    this.isLoading = false;
                    window.history.replaceState({}, '', '{{ route('welcome') }}#kamus');
                    return;
                }

                if (this.abortController) {
                    this.abortController.abort();
                }
                this.abortController = new AbortController();
                this.isLoading = true;

                const params = new URLSearchParams();
                params.set('q', this.q.trim());
                if (this.category && this.category !== 'all') {
                    params.set('category', this.category);
                }

                const queryString = params.toString();
                const fetchUrl = '{{ route('welcome') }}' + (queryString ? '?' + queryString : '');

                // Sinkronisasi URL address bar tanpa reload halaman
                const newUrl = queryString ? '{{ route('welcome') }}?' + queryString + '#kamus' : '{{ route('welcome') }}#kamus';
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

                        if (shouldScroll) {
                            document.getElementById('kamus')?.scrollIntoView({ behavior: 'smooth' });
                        }
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
</head>
<body class="antialiased">

    {{-- ===== NAVBAR ===== --}}
    <header class="border-b sticky top-0 bg-[var(--cream)]/95 backdrop-blur z-50" style="border-color:var(--line)">
        <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-2.5 flex-shrink-0">
                <svg class="w-9 h-9" style="color:var(--soga)" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.4"/>
                    <circle cx="24" cy="24" r="16" stroke="currentColor" stroke-width="0.8"/>
                    <g stroke="currentColor" stroke-width="1.1">
                        <ellipse cx="24" cy="15" rx="3.6" ry="6.5"/>
                        <ellipse cx="24" cy="33" rx="3.6" ry="6.5"/>
                        <ellipse cx="15" cy="24" rx="6.5" ry="3.6"/>
                        <ellipse cx="33" cy="24" rx="6.5" ry="3.6"/>
                    </g>
                    <circle cx="24" cy="24" r="2.4" fill="currentColor"/>
                </svg>
                <span class="font-display font-semibold text-lg tracking-tight">Kramain</span>
            </a>

            <nav class="hidden md:flex items-center gap-8 font-mono text-[13px] uppercase tracking-wide" style="color:var(--soga)">
                <a href="#kamus" class="hover:opacity-70 transition flex items-center gap-1.5 font-semibold text-[var(--wedel)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Kamus Jawa
                </a>
                <a href="#materi" class="hover:opacity-70 transition">Materi</a>
                <a href="#untuk-siapa" class="hover:opacity-70 transition">Untuk Siapa</a>
                <a href="#tentang" class="hover:opacity-70 transition">Tentang</a>
            </nav>

            <div class="flex items-center gap-3 flex-shrink-0">
                @auth
                <a href="{{ route('dashboard') }}"
                   class="px-5 py-2.5 rounded-full text-sm font-semibold text-white transition hover:opacity-90 shadow-sm"
                   style="background:var(--wedel)">
                    Buka Dashboard
                </a>
                @else
                <a href="{{ route('login') }}" class="hidden sm:inline text-sm font-medium hover:opacity-70 transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="px-5 py-2.5 rounded-full text-sm font-semibold text-white transition hover:opacity-90 shadow-sm"
                   style="background:var(--wedel)">
                    Daftar Gratis
                </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ===== HERO ===== --}}
    <section class="batik-bg">
        <div class="max-w-6xl mx-auto px-6 py-16 sm:py-20 grid lg:grid-cols-2 gap-12 items-center">

            <div class="fade-up">
                <span class="inline-flex items-center gap-2 font-mono text-[12px] uppercase tracking-widest px-3 py-1.5 rounded-full border mb-6"
                      style="color:var(--soga); border-color:var(--line); background:var(--card)">
                    <span class="w-1.5 h-1.5 rounded-full" style="background:var(--soga)"></span>
                    Media Pembelajaran & Kamus Unggah-Ungguh
                </span>

                <h1 class="font-display font-bold text-[2.5rem] sm:text-6xl leading-[1.08] tracking-tight mb-6">
                    Kuasai unggah-ungguh<br>bahasa Jawa<span style="color:var(--wedel)">,</span><br>
                    <span style="color:var(--soga)">selangkah demi selangkah</span>
                </h1>

                <p class="text-lg leading-relaxed max-w-md mb-8" style="color:var(--muted)">
                    Lima level pembelajaran, penerjemah tingkatan bahasa instan, dan panduan praktis untuk bicara dengan tepat kepada siapa saja — dari teman sebaya hingga sesepuh.
                </p>

                <div class="flex flex-wrap items-center gap-3 mb-10">
                    <a href="{{ route('register') }}"
                       class="px-7 py-3.5 rounded-full text-white font-semibold text-[15px] transition hover:opacity-90 shadow-sm"
                       style="background:var(--wedel)">
                        Mulai Sinau →
                    </a>
                    <a href="#kamus"
                       class="px-7 py-3.5 rounded-full font-semibold text-[15px] border transition hover:bg-white flex items-center gap-2"
                       style="border-color:var(--soga); color:var(--soga); background:rgba(255,253,248,0.7)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari di Kamus ↓
                    </a>
                    <a href="#materi"
                       class="px-5 py-3.5 rounded-full font-medium text-[14px] transition hover:opacity-70"
                       style="color:var(--muted)">
                        Lihat Fitur
                    </a>
                </div>

                <div class="flex items-center gap-6 sm:gap-8 flex-wrap">
                    <div>
                        <span class="text-2xl font-display font-bold block">5</span>
                        <span class="font-mono text-xs" style="color:var(--muted)">Level</span>
                    </div>
                    <div class="w-px h-8" style="background:var(--line)"></div>
                    <div>
                        <span class="text-2xl font-display font-bold block">500</span>
                        <span class="font-mono text-xs" style="color:var(--muted)">XP</span>
                    </div>
                    <div class="w-px h-8" style="background:var(--line)"></div>
                    <div>
                        <span class="text-2xl font-display font-bold block">4</span>
                        <span class="font-mono text-xs" style="color:var(--muted)">Tingkatan</span>
                    </div>
                    <div class="w-px h-8" style="background:var(--line)"></div>
                    <div>
                        <span class="text-2xl font-display font-bold block" style="color:var(--soga)">{{ number_format($totalWords ?? 955, 0, ',', '.') }}</span>
                        <span class="font-mono text-xs" style="color:var(--muted)">Kosa Kata</span>
                    </div>
                </div>
            </div>

            {{-- Interaktif Stack Kartu Level (Klik kartu untuk menyingkirkan layer teratas) --}}
            <div class="fade-up fade-up-d2 flex flex-col items-center justify-center lg:items-end">
                <div x-data="cardDeck()" class="relative flex flex-col items-center">
                    {{-- Container Deck --}}
                    <div class="relative w-68 h-84 sm:w-76 sm:h-92 flex items-center justify-center select-none cursor-pointer group"
                         @click="nextCard()"
                         title="Klik kartu untuk membuka layer di bawahnya">
                        
                        <template x-for="(card, index) in cards" :key="card.id">
                            <div class="absolute left-1/2 top-1/2 w-50 h-68 sm:w-56 sm:h-78 rounded-[22px] sm:rounded-[26px] border p-6 flex flex-col justify-between shadow-[0_16px_36px_-12px_rgba(40,25,15,0.22)] group-hover:shadow-[0_22px_44px_-10px_rgba(40,25,15,0.28)] transition-shadow select-none"
                                 :style="getCardStyle(card, index) + '; background:' + card.bg + '; border-color:' + card.border + '; color:' + card.text">
                                
                                {{-- Header Kartu --}}
                                <div class="pointer-events-none">
                                    <span class="font-mono text-xs sm:text-[13px] font-semibold tracking-[0.22em] uppercase opacity-85" x-text="card.level"></span>
                                </div>

                                {{-- Aksara Jawa di Tengah --}}
                                <div class="my-auto text-center pointer-events-none py-1">
                                    <span class="font-display text-6xl sm:text-7xl font-normal leading-none block select-none" x-text="card.aksara"></span>
                                </div>

                                {{-- Footer Kartu --}}
                                <div class="pointer-events-none">
                                    <p class="font-display font-bold text-lg sm:text-xl leading-tight" x-text="card.title"></p>
                                    <p class="font-mono text-xs sm:text-sm mt-1.5 tracking-wider opacity-80" x-text="card.xp"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Indikator Titik Level --}}
                    <div class="flex flex-col items-center gap-2 mt-3 select-none">
                        <div class="flex items-center gap-1.5">
                            <template x-for="i in 5" :key="i">
                                <span class="h-1.5 rounded-full transition-all duration-300"
                                      :class="cards[0].id === i ? 'w-6 bg-[var(--soga)]' : 'w-1.5 bg-[var(--line)]'"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== KAMUS KOSA KATA JAWA (DI DEPAN - RINGKAS & HEMAT RUANG) ===== --}}
    <section id="kamus" class="border-y scroll-mt-20 relative" style="border-color:var(--line); background:var(--card)" x-data="dictionaryManager()" x-cloak>
        <div class="max-w-6xl mx-auto px-6 py-12 sm:py-16">

            {{-- Header Bagian Kamus --}}
            <div class="max-w-2xl mb-6">
                <div class="flex flex-wrap items-center gap-3 mb-2.5">
                    <span class="inline-flex items-center gap-1.5 font-mono text-[11px] uppercase tracking-widest px-3 py-1 rounded-full border"
                          style="color:var(--soga); border-color:var(--line); background:var(--cream)">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:var(--soga)"></span>
                        Kamus & Penerjemah Tingkatan
                    </span>

                    <span class="text-xs font-mono px-2.5 py-1 rounded-full border"
                          style="background:#FBF3DF; border-color:#EAD9AE; color:var(--soga)">
                        {{ number_format($totalWords ?? 955, 0, ',', '.') }} Kosa Kata Terdaftar
                    </span>
                </div>

                <h2 class="font-display font-bold text-2.5xl sm:text-3.5xl tracking-tight mb-2" style="color:var(--ink)">
                    Cari Kosa Kata Unggah-Ungguh
                </h2>
                <p class="text-sm leading-relaxed" style="color:var(--muted)">
                    Ketik kata dalam bahasa Indonesia atau Jawa untuk melihat tingkatan ragam:
                    <strong class="font-semibold text-[var(--wedel)]">Ngoko</strong>, 
                    <strong class="font-semibold text-[var(--soga)]">Krama Alus</strong>, dan 
                    <strong class="font-semibold text-[var(--gold)]">Krama Inggil</strong> secara instan.
                </p>
            </div>

            {{-- Penjelasan Ringkas Tingkatan Bahasa (Pill Badges Hemat Ruang) --}}
            <div class="flex flex-wrap items-center gap-2 mb-6 text-xs">
                <span class="font-mono text-[11px] uppercase tracking-wider text-[var(--muted)]">Tingkatan:</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border text-xs font-medium"
                      style="background:#E7EEF5; color:#1E3A5F; border-color:#C9D9E8">
                    <span class="w-1.5 h-1.5 rounded-full" style="background:#1E3A5F"></span>
                    <strong>Ngoko</strong>: Teman sebaya / santai
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border text-xs font-medium"
                      style="background:#DCCFC2; color:#5A3A2A; border-color:#C9B8A8">
                    <span class="w-1.5 h-1.5 rounded-full" style="background:#8B4226"></span>
                    <strong>Krama Alus</strong>: Situasi formal / sopan
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border text-xs font-medium"
                      style="background:#FBF3DF; color:#8B4226; border-color:#EAD9AE">
                    <span class="w-1.5 h-1.5 rounded-full" style="background:#C9A24B"></span>
                    <strong>Krama Inggil</strong>: Penghormatan tertinggi
                </span>
            </div>

            {{-- Form Pencarian Real-Time (Debounce 300ms Langsung ke Database) --}}
            <div class="rounded-2xl border p-4 sm:p-5 shadow-xs" style="background:var(--cream); border-color:var(--line)">
                <form @submit.prevent="searchImmediate()" action="{{ route('welcome') }}" method="GET">
                    <div class="flex flex-col sm:flex-row gap-2.5">
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
                                   id="kamus-search-input"
                                   name="q" 
                                   :value="q"
                                   @input="onSearchInput($event)"
                                   placeholder="Ketik kata (misal: makan, tidur, pergi, tindak, tumbas)..." 
                                   autocomplete="off"
                                   class="w-full pl-11 pr-11 py-3 rounded-xl border text-sm sm:text-base focus:outline-none transition shadow-2xs"
                                   style="background:#FFFDF8; border-color:var(--line); color:var(--ink);"
                                   onfocus="this.style.borderColor='var(--wedel)'; this.style.boxShadow='0 0 0 3px rgba(30,58,95,0.12)'"
                                   onblur="this.style.borderColor='var(--line)'; this.style.boxShadow='none'">

                            {{-- Loading Spinner saat request AJAX berlangsung --}}
                            <div x-show="isLoading" 
                                 class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none"
                                 style="display:none;">
                                <svg class="animate-spin w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>

                            {{-- Tombol Hapus / Clear kata kunci --}}
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
                                    class="w-full py-3 px-3.5 rounded-xl border text-sm focus:outline-none transition cursor-pointer shadow-2xs"
                                    style="background:#FFFDF8; border-color:var(--line); color:var(--ink);">
                                <option value="all">Semua Kolom</option>
                                <option value="indonesia">Bahasa Indonesia</option>
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

                    {{-- Saran Kata Kunci Cepat --}}
                    <div class="flex flex-wrap items-center justify-between gap-2.5 mt-3 pt-3 border-t text-xs" style="border-color:var(--line)">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="font-mono uppercase tracking-wider text-[11px]" style="color:var(--muted)">Saran Kata:</span>
                            @foreach($quickKeywords ?? ['Makan', 'Tidur', 'Pergi', 'Rumah', 'Bicara', 'Melihat', 'Minum', 'Beli', 'Datang', 'Nama', 'Ibu', 'Ayah'] as $keyword)
                            <button type="button" 
                                    @click="searchImmediate('{{ $keyword }}')"
                                    :style="q.toLowerCase() === '{{ strtolower($keyword) }}' 
                                        ? 'background:var(--wedel); color:#fff; border-color:var(--wedel)' 
                                        : 'background:var(--card); color:var(--ink); border-color:var(--line)'"
                                    class="px-2.5 py-1 text-xs rounded-full border transition hover:scale-105 cursor-pointer shadow-2xs">
                                {{ $keyword }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            {{-- ===== HASIL TERJEMAHAN (HANYA MUNCUL KETIKA ADA PENCARIAN / TIDAK MEMAKAN RUANG SAAT AWAL) ===== --}}
            <div x-show="q.trim() !== ''" class="mt-6" style="display:none;">
                <div class="flex items-center justify-between gap-3 mb-3 pb-2 border-b" style="border-color:var(--line)">
                    <div class="flex items-center gap-2">
                        <span class="font-display font-bold text-sm sm:text-base text-[var(--ink)]">
                            Hasil Terjemahan: <span class="italic text-[var(--soga)]" x-text="'&ldquo;' + q + '&rdquo;'"></span>
                        </span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-mono border"
                              style="background:#FBF3DF; border-color:#EAD9AE; color:var(--soga)"
                              x-text="words.length + ' kata'">
                        </span>
                        <span x-show="isLoading" class="inline-flex items-center gap-1.5 text-xs font-mono text-amber-700 animate-pulse ml-1" style="display:none;">
                            <svg class="animate-spin w-3.5 h-3.5 text-amber-700" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Mencari...</span>
                        </span>
                    </div>

                    <button type="button" 
                            @click="clearSearch()" 
                            class="text-xs font-medium inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border hover:bg-[var(--cream)] transition cursor-pointer"
                            style="border-color:var(--line); color:var(--muted)">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tutup Hasil
                    </button>
                </div>

                {{-- Kartu Hasil Pencarian Ringkas --}}
                <div x-show="words.length > 0" class="space-y-3">
                    <template x-for="item in words" :key="item.id">
                        <div class="rounded-xl border p-4 transition shadow-2xs hover:shadow-xs" 
                             style="background:var(--cream); border-color:var(--line)">
                            
                            {{-- Baris Judul Kata Indonesia & Tombol Salin --}}
                            <div class="flex items-center justify-between gap-3 mb-3 pb-2 border-b" style="border-color:var(--line)">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-mono uppercase tracking-wider text-[var(--muted)]">Bahasa Indonesia:</span>
                                    <h3 class="font-display font-bold text-base sm:text-lg text-[var(--ink)]" x-html="highlight(item.indonesia)"></h3>
                                </div>
                                <button type="button" 
                                        @click="copy(item.indonesia + ' — Ngoko: ' + (item.ngoko || '—') + ', Krama: ' + (item.krama_alus || '—') + ', Inggil: ' + (item.krama_inggil || '—'), 'Kamus')"
                                        class="text-xs font-mono inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border hover:bg-[var(--card)] transition cursor-pointer"
                                        style="border-color:var(--line); color:var(--muted); background:var(--card)"
                                        title="Salin semua tingkatan kata ini">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                    </svg>
                                    <span>Salin Semua</span>
                                </button>
                            </div>

                            {{-- 3 Kolom Ragam Bahasa Jawa --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                {{-- Ngoko --}}
                                <div class="flex items-center justify-between p-3 rounded-xl border"
                                     style="background:#E7EEF5; border-color:#C9D9E8">
                                    <div>
                                        <span class="text-[10px] font-mono uppercase tracking-wider block text-[#1E3A5F]">Ngoko (Santai)</span>
                                        <span class="font-mono font-medium text-sm sm:text-base text-[#1E3A5F]" x-html="highlight(item.ngoko)"></span>
                                    </div>
                                    <button type="button" 
                                            @click="copy(item.ngoko, 'Ngoko')"
                                            class="p-1.5 rounded-lg border hover:bg-white transition cursor-pointer text-[#1E3A5F]"
                                            style="border-color:#C9D9E8"
                                            title="Salin Ngoko">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Krama Alus --}}
                                <div class="flex items-center justify-between p-3 rounded-xl border"
                                     style="background:#DCCFC2; border-color:#C9B8A8">
                                    <div>
                                        <span class="text-[10px] font-mono uppercase tracking-wider block text-[#5A3A2A]">Krama Alus (Sopan)</span>
                                        <span class="font-mono font-medium text-sm sm:text-base text-[#5A3A2A]" x-html="highlight(item.krama_alus)"></span>
                                    </div>
                                    <button type="button" 
                                            @click="copy(item.krama_alus, 'Krama Alus')"
                                            class="p-1.5 rounded-lg border hover:bg-white transition cursor-pointer text-[#5A3A2A]"
                                            style="border-color:#C9B8A8"
                                            title="Salin Krama Alus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Krama Inggil --}}
                                <div class="flex items-center justify-between p-3 rounded-xl border"
                                     style="background:#FBF3DF; border-color:#EAD9AE">
                                    <div>
                                        <span class="text-[10px] font-mono uppercase tracking-wider block text-[#8B4226]">Krama Inggil (Hormat)</span>
                                        <span class="font-mono font-bold text-sm sm:text-base text-[#8B4226]" x-html="highlight(item.krama_inggil)"></span>
                                    </div>
                                    <button type="button" 
                                            @click="copy(item.krama_inggil, 'Krama Inggil')"
                                            class="p-1.5 rounded-lg border hover:bg-white transition cursor-pointer text-[#8B4226]"
                                            style="border-color:#EAD9AE"
                                            title="Salin Krama Inggil">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </template>
                </div>

                {{-- Jika kata tidak ditemukan --}}
                <div x-show="words.length === 0 && !isLoading" 
                     class="rounded-xl border p-6 text-center" 
                     style="background:var(--cream); border-color:var(--line)">
                    <p class="text-sm font-medium mb-1 text-[var(--ink)]">
                        Kata <strong class="text-[var(--soga)]" x-text="'&ldquo;' + q + '&rdquo;'"></strong> belum ada di kamus.
                    </p>
                    <p class="text-xs text-[var(--muted)]">
                        Coba gunakan bentuk kata dasar atau periksa kembali ejaannya.
                    </p>
                </div>
            </div>

        </div>

        {{-- Toast Salin Notifikasi --}}
        <div x-show="showToast"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-3"
             class="fixed bottom-6 right-6 z-50 rounded-2xl border px-4 py-3 shadow-lg flex items-center gap-2.5 text-sm"
             style="background:var(--wedel); border-color:var(--wedel-deep); color:#fff; display:none;">
            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span x-text="copiedText"></span>
        </div>
    </section>

    {{-- ===== FITUR ===== --}}
    <section id="materi" class="max-w-6xl mx-auto px-6 py-20">
        <div class="max-w-xl mb-12 fade-up">
            <span class="font-mono text-[12px] uppercase tracking-widest" style="color:var(--soga)">Fitur Utama</span>
            <h2 class="font-display font-bold text-3xl sm:text-4xl mt-2 tracking-tight">
                Apa yang kamu dapatkan
            </h2>
        </div>

        <div class="grid sm:grid-cols-2 gap-5">

            <div class="rounded-2xl p-7 border fade-up" style="background:var(--card); border-color:var(--line)">
                <div class="w-11 h-11 rounded-full border flex items-center justify-center mb-5" style="border-color:var(--soga); color:var(--soga)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                              d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display font-semibold text-lg mb-2">Gamifikasi Bermakna</h3>
                <p class="text-sm leading-relaxed" style="color:var(--muted)">
                    Kumpulkan XP dan lencana di setiap level, lalu pantau posisimu
                    di papan peringkat bersama pengguna lain.
                </p>
            </div>

            <div class="rounded-2xl p-7 border fade-up" style="background:var(--card); border-color:var(--line)">
                <div class="w-11 h-11 rounded-full border flex items-center justify-center mb-5" style="border-color:var(--soga); color:var(--soga)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86
                                 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418
                                 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="font-display font-semibold text-lg mb-2">Skenario Percakapan Nyata</h3>
                <p class="text-sm leading-relaxed" style="color:var(--muted)">
                    Belajar dari situasi sehari-hari — bicara dengan guru, teman,
                    atau orang tua — bukan sekadar hafalan kosakata.
                </p>
            </div>

            <div class="rounded-2xl p-7 border fade-up" style="background:var(--card); border-color:var(--line)">
                <div class="w-11 h-11 rounded-full border flex items-center justify-center mb-5" style="border-color:var(--soga); color:var(--soga)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                              d="M4 20h4v-6H4v6zm6 0h4V10h-4v10zm6 0h4V4h-4v16z"/>
                    </svg>
                </div>
                <h3 class="font-display font-semibold text-lg mb-2">Sistem Level Berjenjang</h3>
                <p class="text-sm leading-relaxed" style="color:var(--muted)">
                    Level berikutnya baru terbuka setelah skormu mencapai 75%
                    — memastikan pemahaman sebelum melangkah lebih jauh.
                </p>
            </div>

            <div class="rounded-2xl p-7 border fade-up" style="background:var(--card); border-color:var(--line)">
                <div class="w-11 h-11 rounded-full border flex items-center justify-center mb-5" style="border-color:var(--soga); color:var(--soga)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0
                                 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2
                                 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="font-display font-semibold text-lg mb-2">Kuis Multi-Tipe</h3>
                <p class="text-sm leading-relaxed" style="color:var(--muted)">
                    Enam jenis soal — pilihan ganda, isi rumpang, susun kalimat,
                    dan lainnya — menguji pemahaman dari berbagai sisi.
                </p>
            </div>
        </div>
    </section>

    {{-- ===== UNTUK SIAPA ===== --}}
    <section id="untuk-siapa" class="border-y" style="border-color:var(--line); background:var(--card)">
        <div class="max-w-6xl mx-auto px-6 py-20">
            <div class="max-w-xl mb-12">
                <span class="font-mono text-[12px] uppercase tracking-widest" style="color:var(--soga)">Untuk Siapa</span>
                <h2 class="font-display font-bold text-3xl sm:text-4xl mt-2 tracking-tight">
                    Dibuat untuk siswa-siswa di DIY
                </h2>
            </div>

            <div class="grid sm:grid-cols-3 gap-5">
                <div class="rounded-2xl p-7 border" style="border-color:var(--line); background:var(--cream)">
                    <p class="font-mono text-xs mb-3" style="color:var(--soga)">01</p>
                    <h3 class="font-display font-semibold text-lg mb-2">Siswa SMA</h3>
                    <p class="text-sm leading-relaxed" style="color:var(--muted)">
                        Belajar mandiri sesuai kecepatanmu sendiri, kapan saja
                        lewat peramban tanpa perlu instalasi apa pun.
                    </p>
                </div>
                <div class="rounded-2xl p-7 border" style="border-color:var(--line); background:var(--cream)">
                    <p class="font-mono text-xs mb-3" style="color:var(--soga)">02</p>
                    <h3 class="font-display font-semibold text-lg mb-2">Guru Muatan Lokal</h3>
                    <p class="text-sm leading-relaxed" style="color:var(--muted)">
                        Gunakan sebagai media pendukung pembelajaran bahasa Jawa
                        yang selaras dengan Kurikulum Merdeka.
                    </p>
                </div>
                <div class="rounded-2xl p-7 border" style="border-color:var(--line); background:var(--cream)">
                    <p class="font-mono text-xs mb-3" style="color:var(--soga)">03</p>
                    <h3 class="font-display font-semibold text-lg mb-2">Masyarakat Umum</h3>
                    <p class="text-sm leading-relaxed" style="color:var(--muted)">
                        Ikut menjaga kelestarian unggah-ungguh bahasa Jawa
                        di tengah kehidupan sehari-hari.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CTA ===== --}}
    <section id="tentang" class="max-w-6xl mx-auto px-6 py-20">
        <div class="rounded-3xl px-8 py-14 sm:px-16 text-center" style="background:var(--wedel)">
            <h2 class="font-display font-bold text-3xl sm:text-4xl text-white mb-4">
                Siap mulai level pertamamu?
            </h2>
            <p class="mb-8 max-w-md mx-auto" style="color:#C9D9E8">
                Daftar gratis, mulai dari Ngoko Lugu, dan kumpulkan XP pertamamu hari ini.
            </p>
            <a href="{{ route('register') }}"
               class="inline-block px-8 py-3.5 rounded-full font-semibold text-[15px] transition hover:opacity-90 shadow-md"
               style="background:var(--gold); color:var(--ink)">
                Daftar Sekarang
            </a>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="border-t" style="border-color:var(--line)">
        <div class="max-w-6xl mx-auto px-6 py-12 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div class="flex items-center gap-2.5">
                <svg class="w-7 h-7" style="color:var(--soga)" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.4"/>
                    <g stroke="currentColor" stroke-width="1.1">
                        <ellipse cx="24" cy="15" rx="3.6" ry="6.5"/>
                        <ellipse cx="24" cy="33" rx="3.6" ry="6.5"/>
                        <ellipse cx="15" cy="24" rx="6.5" ry="3.6"/>
                        <ellipse cx="33" cy="24" rx="6.5" ry="3.6"/>
                    </g>
                    <circle cx="24" cy="24" r="2.4" fill="currentColor"/>
                </svg>
                <div>
                    <p class="font-display font-semibold text-sm">Kramain</p>
                    <p class="text-xs" style="color:var(--muted)">Belajar unggah-ungguh bahasa Jawa & Kamus Kosa Kata</p>
                </div>
            </div>
            <p class="font-mono text-xs" style="color:var(--muted)">
                © {{ date('Y') }} Kramain · Fauzan Dimas
            </p>
        </div>
    </footer>

</body>
</html>
