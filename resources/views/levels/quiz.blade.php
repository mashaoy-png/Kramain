@extends('layouts.app')

@section('title', 'Kuis Level ' . $level->level_number)

@section('content')

<div class="mb-6 flex items-center justify-between gap-4">
    <div>
        <a href="{{ route('levels.show', $level->id) }}"
           class="text-sm hover:opacity-70 transition flex items-center gap-1 mb-1" style="color:var(--muted)">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke materi
        </a>
        <h1 class="font-display font-bold text-xl" style="color:var(--ink)">
            Kuis Level {{ $level->level_number }} — {{ $level->name }}
        </h1>
    </div>
    <div class="text-sm font-medium flex-shrink-0" style="color:var(--muted)">
        Soal <span id="q-current" class="font-bold" style="color:var(--ink)">1</span>
        dari <span class="font-bold" style="color:var(--ink)">{{ count($quizzes) }}</span>
    </div>
</div>

<div class="h-2 rounded-full overflow-hidden mb-8" style="background:var(--line)">
    <div id="q-progress-bar"
         class="h-full rounded-full transition-all duration-300 ease-out"
         style="width: {{ count($quizzes) > 0 ? (1 / count($quizzes) * 100) : 0 }}%; background:var(--wedel)"></div>
</div>

<form id="quiz-form" method="POST" action="{{ route('levels.quiz.submit', $level->id) }}">
    @csrf

    @foreach($quizzes as $idx => $quiz)
    <div class="quiz-slide {{ $idx > 0 ? 'hidden' : '' }}" data-index="{{ $idx }}">

        <div class="rounded-2xl border p-6 sm:p-8 mb-6" style="background:var(--card); border-color:var(--line)">

            <div class="mb-4">
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border"
                      style="color:var(--soga); background:#FBF3DF; border-color:#EAD9AE">
                    @switch($quiz->question_type)
                        @case('mc')         Pilihan Ganda @break
                        @case('fill_blank') Isi Rumpang @break
                        @case('arrange')    Susun Kalimat @break
                        @case('identify')   Identifikasi Tingkatan @break
                        @case('dialogue')   Dialog Rumpang @break
                        @case('error_find') Temukan Kesalahan @break
                        @default            Soal @break
                    @endswitch
                </span>
            </div>

            <p class="text-base font-medium leading-relaxed mb-6" style="color:var(--ink)">
                {{ $quiz->question_text }}
            </p>

            {{-- PILIHAN GANDA (mc, identify) --}}
            @if(in_array($quiz->question_type, ['mc', 'identify']))
            <div class="space-y-3" role="radiogroup">
                @foreach($quiz->options as $option)
                <label class="flex items-center gap-3.5 p-4 rounded-xl border cursor-pointer transition
                              hover:border-[var(--wedel)] hover:bg-[#EAF0F6]
                              has-[:checked]:border-[var(--wedel)] has-[:checked]:bg-[#EAF0F6]"
                       style="border-color:var(--line)">
                    <input type="radio"
                           name="answers[{{ $quiz->id }}]"
                           value="{{ $option->id }}"
                           class="w-4 h-4 border-[var(--line)] flex-shrink-0"
                           style="accent-color:var(--wedel)"
                           onchange="markAnswered({{ $idx }})">
                    <span class="text-sm leading-relaxed" style="color:var(--ink)">{{ $option->option_text }}</span>
                </label>
                @endforeach
            </div>

            {{-- ISI RUMPANG (fill_blank, dialogue) --}}
            @elseif(in_array($quiz->question_type, ['fill_blank', 'dialogue']))
            <div>
                <textarea name="answers[{{ $quiz->id }}]"
                          rows="{{ $quiz->question_type === 'dialogue' ? 4 : 2 }}"
                          required
                          class="w-full px-4 py-3 rounded-xl border text-sm resize-none transition
                                 focus:outline-none focus:ring-2 focus:ring-[var(--wedel)] focus:border-transparent"
                          style="border-color:var(--line); color:var(--ink)"
                          placeholder="{{ $quiz->question_type === 'dialogue' ? 'Tulis dialog lengkap di sini...' : 'Ketik jawaban kamu di sini...' }}"
                          oninput="markAnswered({{ $idx }})"></textarea>
                @if($quiz->question_type === 'fill_blank')
                <p class="text-xs mt-2" style="color:var(--muted)">Ketik satu kata atau frasa yang paling tepat untuk melengkapi kalimat.</p>
                @endif
            </div>

            {{-- SUSUN KALIMAT (arrange) --}}
            @elseif($quiz->question_type === 'arrange')
            <div x-data="arrangeQuiz({{ $quiz->id }}, {{ collect($quiz->options)->pluck('option_text')->toJson() }})"
                 class="space-y-4">
                <div>
                    <p class="text-xs font-medium mb-2 uppercase tracking-wide" style="color:var(--muted)">Kata tersedia — klik untuk memilih</p>
                    <div class="flex flex-wrap gap-2 min-h-12 p-3 rounded-xl border border-dashed" style="background:var(--cream); border-color:var(--line)">
                        <template x-for="(word, i) in available" :key="i">
                            <button type="button" @click="pick(i)"
                                    class="px-3 py-1.5 text-sm rounded-lg border transition hover:border-[var(--wedel)] hover:bg-[#EAF0F6]"
                                    style="background:var(--card); border-color:var(--line); color:var(--ink)">
                                <span x-text="word"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium mb-2 uppercase tracking-wide" style="color:var(--muted)">Urutan jawabanmu — klik untuk mengembalikan</p>
                    <div class="flex flex-wrap gap-2 min-h-12 p-3 rounded-xl border" style="background:#EAF0F6; border-color:#C9D9E8">
                        <template x-for="(word, i) in chosen" :key="i">
                            <button type="button" @click="unpick(i)"
                                    class="px-3 py-1.5 text-white text-sm rounded-lg transition hover:opacity-90"
                                    style="background:var(--wedel)">
                                <span x-text="word"></span>
                            </button>
                        </template>
                        <span x-show="chosen.length === 0" class="text-xs self-center" style="color:var(--muted)">
                            Pilih kata dari atas untuk menyusun kalimat
                        </span>
                    </div>
                </div>
                <input type="hidden" :name="'answers[{{ $quiz->id }}]'" :value="chosen.join(' ')">
            </div>

            {{-- TEMUKAN KESALAHAN (error_find) --}}
            @elseif($quiz->question_type === 'error_find')
            <div class="space-y-3" role="radiogroup">
                @foreach($quiz->options as $option)
                <label class="flex items-center gap-3.5 p-4 rounded-xl border cursor-pointer transition
                              hover:border-red-300 hover:bg-red-50/50
                              has-[:checked]:border-red-400 has-[:checked]:bg-red-50"
                       style="border-color:var(--line)">
                    <input type="radio"
                           name="answers[{{ $quiz->id }}]"
                           value="{{ $option->id }}"
                           class="w-4 h-4 text-red-500 border-[var(--line)] focus:ring-red-500 focus:ring-offset-0 flex-shrink-0"
                           onchange="markAnswered({{ $idx }})">
                    <span class="text-sm font-mono leading-relaxed" style="color:var(--ink)">{{ $option->option_text }}</span>
                </label>
                @endforeach
            </div>
            @endif
        </div>

        <div class="flex items-center justify-between gap-4">
            <button type="button" onclick="prevQ()"
                    class="{{ $idx === 0 ? 'invisible' : '' }} flex items-center gap-2 px-5 py-2.5
                           text-sm font-medium rounded-full border transition hover:opacity-80"
                    style="color:var(--soga); border-color:var(--soga)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Sebelumnya
            </button>

            @if($idx < count($quizzes) - 1)
            <button type="button" onclick="nextQ()"
                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white
                           rounded-full transition hover:opacity-90" style="background:var(--wedel)">
                Berikutnya
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            @else
            <button type="button" onclick="submitQuiz()"
                    class="flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white
                           bg-emerald-600 rounded-full hover:bg-emerald-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Kumpulkan Jawaban
            </button>
            @endif
        </div>
    </div>
    @endforeach

    <div class="flex justify-center gap-1.5 mt-6">
        @foreach($quizzes as $idx => $quiz)
        <button type="button" onclick="goToQ({{ $idx }})"
                id="dot-{{ $idx }}"
                class="w-2 h-2 rounded-full transition-all"
                style="background:{{ $idx === 0 ? 'var(--wedel)' : 'var(--line)' }}; {{ $idx === 0 ? 'width:16px' : '' }}"
                aria-label="Soal {{ $idx + 1 }}">
        </button>
        @endforeach
    </div>
</form>

<div id="submit-confirm" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
    <div class="rounded-2xl p-6 max-w-sm w-full shadow-xl" style="background:var(--card)">
        <h3 class="font-display font-bold mb-2" style="color:var(--ink)">Kumpulkan jawaban?</h3>
        <p class="text-sm mb-5" id="confirm-unanswered" style="color:var(--muted)"></p>
        <div class="flex gap-3">
            <button type="button" onclick="document.getElementById('submit-confirm').classList.add('hidden')"
                    class="flex-1 px-4 py-2.5 text-sm font-medium rounded-xl transition hover:opacity-80"
                    style="color:var(--ink); background:var(--cream)">
                Cek Lagi
            </button>
            <button type="button" onclick="document.getElementById('quiz-form').submit()"
                    class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-emerald-600
                           rounded-xl hover:bg-emerald-700 transition">
                Ya, Kumpulkan
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const totalQ    = {{ count($quizzes) }};
let currentQ    = 0;
const answered  = new Set();

function showQ(idx) {
    document.querySelectorAll('.quiz-slide').forEach((el, i) => {
        el.classList.toggle('hidden', i !== idx);
    });
    document.getElementById('q-current').textContent = idx + 1;
    const pct = ((idx + 1) / totalQ) * 100;
    document.getElementById('q-progress-bar').style.width = pct + '%';

    document.querySelectorAll('[id^="dot-"]').forEach((dot, i) => {
        if (i === idx) { dot.style.background = 'var(--wedel)'; dot.style.width = '16px'; }
        else if (answered.has(i)) { dot.style.background = '#34D399'; dot.style.width = '8px'; }
        else { dot.style.background = 'var(--line)'; dot.style.width = '8px'; }
    });
    currentQ = idx;
}

function nextQ()    { if (currentQ < totalQ - 1) showQ(currentQ + 1); }
function prevQ()    { if (currentQ > 0)           showQ(currentQ - 1); }
function goToQ(idx) { showQ(idx); }

function markAnswered(idx) {
    answered.add(idx);
    document.querySelectorAll('[id^="dot-"]').forEach((dot, i) => {
        if (i !== currentQ && answered.has(i)) { dot.style.background = '#34D399'; }
    });
}

function submitQuiz() {
    const unanswered = totalQ - answered.size;
    const msg = unanswered > 0
        ? `${unanswered} soal belum dijawab. Soal yang belum dijawab akan dianggap salah.`
        : 'Semua soal sudah dijawab. Lanjutkan mengumpulkan?';
    document.getElementById('confirm-unanswered').textContent = msg;
    document.getElementById('submit-confirm').classList.remove('hidden');
}

function arrangeQuiz(quizId, words) {
    return {
        available: [...words],
        chosen: [],
        pick(i) { this.chosen.push(this.available.splice(i, 1)[0]); markAnswered(currentQ); },
        unpick(i) { this.available.push(this.chosen.splice(i, 1)[0]); }
    };
}
</script>
@endpush
