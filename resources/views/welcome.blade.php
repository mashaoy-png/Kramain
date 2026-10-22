<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kramain — Kuasai Unggah-Ungguh Bahasa Jawa</title>
    <meta name="description" content="Media pembelajaran unggah-ungguh bahasa Jawa berbasis gamifikasi dengan sistem level bertahap, dirancang untuk siswa SMA di Daerah Istimewa Yogyakarta.">

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
                <a href="{{ route('dictionary.index') }}" class="hover:opacity-70 transition flex items-center gap-1.5 font-semibold text-[var(--wedel)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Kamus
                </a>
                <a href="#materi" class="hover:opacity-70 transition">Materi</a>
                <a href="#untuk-siapa" class="hover:opacity-70 transition">Untuk Siapa</a>
                <a href="#tentang" class="hover:opacity-70 transition">Tentang</a>
            </nav>

            <div class="flex items-center gap-3 flex-shrink-0">
                @auth
                <a href="{{ route('dashboard') }}"
                   class="px-5 py-2.5 rounded-full text-sm font-semibold text-white transition hover:opacity-90"
                   style="background:var(--wedel)">
                    Buka Dashboard
                </a>
                @else
                <a href="{{ route('login') }}" class="hidden sm:inline text-sm font-medium hover:opacity-70 transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="px-5 py-2.5 rounded-full text-sm font-semibold text-white transition hover:opacity-90"
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
                    Media Pembelajaran Interaktif
                </span>

                <h1 class="font-display font-bold text-[2.5rem] sm:text-6xl leading-[1.08] tracking-tight mb-6">
                    Kuasai unggah-ungguh<br>bahasa Jawa<span style="color:var(--wedel)">,</span><br>
                    <span style="color:var(--soga)">selangkah demi selangkah</span>
                </h1>

                <p class="text-lg leading-relaxed max-w-md mb-8" style="color:var(--muted)">
                    Lima level, empat tingkatan bahasa, satu tujuan: bicara dengan tepat
                    kepada siapa saja — dari teman sebaya sampai kepala sekolah.
                </p>

                <div class="flex flex-wrap items-center gap-3 mb-10">
                    <a href="{{ route('register') }}"
                       class="px-7 py-3.5 rounded-full text-white font-semibold text-[15px] transition hover:opacity-90"
                       style="background:var(--wedel)">
                        Mulai Sinau →
                    </a>
                    <a href="#materi"
                       class="px-7 py-3.5 rounded-full font-semibold text-[15px] border transition hover:bg-white"
                       style="border-color:var(--soga); color:var(--soga)">
                        Lihat Materi
                    </a>
                </div>

                <div class="flex items-center gap-8">
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
                </div>
            </div>

            {{-- Fan kartu level — pengganti ilustrasi maskot, menggambarkan progression sesungguhnya --}}
            <div class="fade-up fade-up-d2 flex justify-center lg:justify-end">
                <div class="card-fan w-full max-w-sm">
                    <div class="lv lv1">
                        <span class="font-mono text-[10px] font-medium tracking-wide">LEVEL 1</span>
                        <span class="font-display text-3xl leading-none" aria-hidden="true">ꦤ</span>
                        <div>
                            <p class="font-display font-semibold text-[13px] leading-tight">Ngoko Lugu</p>
                            <p class="font-mono text-[10px] mt-1 opacity-70">+50 XP</p>
                        </div>
                    </div>
                    <div class="lv lv2">
                        <span class="font-mono text-[10px] font-medium tracking-wide">LEVEL 2</span>
                        <span class="font-display text-3xl leading-none" aria-hidden="true">ꦭ</span>
                        <div>
                            <p class="font-display font-semibold text-[13px] leading-tight">Ngoko Alus</p>
                            <p class="font-mono text-[10px] mt-1 opacity-70">+75 XP</p>
                        </div>
                    </div>
                    <div class="lv lv3">
                        <span class="font-mono text-[10px] font-medium tracking-wide">LEVEL 3</span>
                        <span class="font-display text-3xl leading-none" aria-hidden="true">ꦏ</span>
                        <div>
                            <p class="font-display font-semibold text-[13px] leading-tight">Krama Lugu</p>
                            <p class="font-mono text-[10px] mt-1 opacity-70">+100 XP</p>
                        </div>
                    </div>
                    <div class="lv lv4">
                        <span class="font-mono text-[10px] font-medium tracking-wide">LEVEL 4</span>
                        <span class="font-display text-3xl leading-none" aria-hidden="true">ꦫ</span>
                        <div>
                            <p class="font-display font-semibold text-[13px] leading-tight">Krama Alus</p>
                            <p class="font-mono text-[10px] mt-1 opacity-70">+125 XP</p>
                        </div>
                    </div>
                    <div class="lv lv5">
                        <span class="font-mono text-[10px] font-medium tracking-wide">LEVEL 5</span>
                        <span class="font-display text-3xl leading-none" aria-hidden="true">ꦩ</span>
                        <div>
                            <p class="font-display font-semibold text-[13px] leading-tight">Evaluasi</p>
                            <p class="font-mono text-[10px] mt-1 opacity-70">+150 XP · Sertifikat</p>
                        </div>
                    </div>
                </div>
            </div>
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
               class="inline-block px-8 py-3.5 rounded-full font-semibold text-[15px] transition hover:opacity-90"
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
                    <p class="text-xs" style="color:var(--muted)">Belajar unggah-ungguh bahasa Jawa</p>
                </div>
            </div>
            <p class="font-mono text-xs" style="color:var(--muted)">
                © {{ date('Y') }} Kramain · Fauzan Dimas
            </p>
        </div>
    </footer>

</body>
</html>
