<?php

use App\Http\Controllers\DictionaryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DictionaryController::class, 'welcome'])->name('welcome');

// Kamus Kosa Kata (Translator Indonesia - Jawa)
Route::get('/kamus', [DictionaryController::class, 'index'])->name('dictionary.index');
Route::get('/kamus/cari', [DictionaryController::class, 'search'])->name('dictionary.search');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

function getDefaultProgress() {
    return [
        1 => (object)[ 'status' => 'unlocked', 'score' => null, 'xp_earned' => 0 ],
        2 => (object)[ 'status' => 'locked', 'score' => null, 'xp_earned' => 0 ],
        3 => (object)[ 'status' => 'locked', 'score' => null, 'xp_earned' => 0 ],
        4 => (object)[ 'status' => 'locked', 'score' => null, 'xp_earned' => 0 ],
        5 => (object)[ 'status' => 'locked', 'score' => null, 'xp_earned' => 0 ],
    ];
}

function getUserProgress() {
    $progress = session('userProgress');
    if (!$progress || empty($progress)) {
        $progress = getDefaultProgress();
        session(['userProgress' => $progress]);
    }
    
    $objectProgress = [];
    foreach ($progress as $id => $p) {
        $objectProgress[$id] = (object) $p;
    }
    return $objectProgress;
}

Route::get('/dashboard', function () {
    $levels = [
        (object)[ 'id' => 1, 'level_number' => 1, 'name' => 'Ngoko Lugu', 'description' => 'Percakapan informal.', 'xp_reward' => 50 ],
        (object)[ 'id' => 2, 'level_number' => 2, 'name' => 'Ngoko Alus', 'description' => 'Rasa hormat ringan.', 'xp_reward' => 75 ],
        (object)[ 'id' => 3, 'level_number' => 3, 'name' => 'Krama Lugu', 'description' => 'Formal dasar.', 'xp_reward' => 100 ],
        (object)[ 'id' => 4, 'level_number' => 4, 'name' => 'Krama Alus', 'description' => 'Formal tertinggi.', 'xp_reward' => 125 ],
        (object)[ 'id' => 5, 'level_number' => 5, 'name' => 'Evaluasi', 'description' => 'Ujian komprehensif.', 'xp_reward' => 150 ],
    ];

    $userProgress = getUserProgress();

    $totalXp = collect($userProgress)->sum('xp_earned');
    $completedCount = collect($userProgress)->where('status', 'completed')->count();

    return view('dashboard.index', [
        'totalXp' => $totalXp,
        'completedCount' => $completedCount,
        'levels' => $levels,
        'userProgress' => $userProgress,
    ]);
})->name('dashboard');

Route::post('/login', function () {
    \Illuminate\Support\Facades\Auth::loginUsingId(1);
    return redirect()->route('dashboard');
});

Route::post('/register', function () {
    \Illuminate\Support\Facades\Auth::loginUsingId(1);
    session()->forget('userProgress');
    return redirect()->route('dashboard');
});

Route::get('/leaderboard', function () {
    $leaderboard = collect([
        (object)['id' => 2, 'name' => 'Budi Setiawan', 'total_xp' => 450, 'completed_count' => 4],
        (object)['id' => 3, 'name' => 'Siti Aminah', 'total_xp' => 300, 'completed_count' => 3],
        (object)['id' => 4, 'name' => 'Joko Widodo', 'total_xp' => 250, 'completed_count' => 2],
        (object)['id' => 1, 'name' => auth()->user()?->name ?? 'Siswa Pintar', 'total_xp' => collect(getUserProgress())->sum('xp_earned'), 'completed_count' => collect(getUserProgress())->where('status', 'completed')->count()],
    ])->sortByDesc('total_xp')->values();
    
    $currentUserRank = $leaderboard->search(fn($user) => $user->id === 1) + 1;

    return view('leaderboard.index', [
        'leaderboard' => $leaderboard,
        'currentUserRank' => $currentUserRank
    ]);
})->name('leaderboard');

Route::get('/profile', function () {
    $userProgress = getUserProgress();
    $totalXp = collect($userProgress)->sum('xp_earned');
    $completedCount = collect($userProgress)->where('status', 'completed')->count();

    $user = (object)[
        'name' => auth()->user()?->name ?? 'Siswa Pintar',
        'email' => auth()->user()?->email ?? 'siswa@example.com',
        'created_at' => \Carbon\Carbon::parse('2025-08-01'),
        'certificate_available' => $completedCount == 5,
    ];

    $allBadges = collect([
        (object)['id' => 1, 'name' => 'Pemula Ngoko'],
        (object)['id' => 2, 'name' => 'Mahir Ngoko'],
        (object)['id' => 3, 'name' => 'Pemula Krama'],
        (object)['id' => 4, 'name' => 'Mahir Krama'],
        (object)['id' => 5, 'name' => 'Pakar Jawa'],
    ]);

    $earnedBadges = $allBadges->take($completedCount);

    $progressList = collect($userProgress)->map(function($progress, $id) {
        $progress = (object) $progress;
        $levelNames = [1 => 'Ngoko Lugu', 2 => 'Ngoko Alus', 3 => 'Krama Lugu', 4 => 'Krama Alus', 5 => 'Evaluasi'];
        return (object)[
            'status' => $progress->status,
            'score' => $progress->score,
            'xp_earned' => $progress->xp_earned,
            'attempts' => $progress->status === 'completed' ? 1 : 0,
            'level' => (object)['level_number' => $id, 'name' => $levelNames[$id]]
        ];
    });

    return view('profile.index', [
        'user' => $user,
        'totalXp' => $totalXp,
        'completedCount' => $completedCount,
        'allBadges' => $allBadges,
        'earnedBadges' => $earnedBadges,
        'progressList' => $progressList,
    ]);
})->name('profile');

Route::get('/certificate', function () {
    return view('certificate.show');
})->name('certificate');

Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    session()->flush();
    return redirect()->route('welcome');
})->name('logout');

Route::get('/levels/{id}', function ($id) {
    $levelNames = [1 => 'Ngoko Lugu', 2 => 'Ngoko Alus', 3 => 'Krama Lugu', 4 => 'Krama Alus', 5 => 'Evaluasi'];
    $level = (object)[ 'id' => $id, 'level_number' => $id, 'name' => $levelNames[$id] ?? 'Materi', 'description' => 'Percakapan informal dengan teman sebaya.', 'xp_reward' => 50 + (($id - 1) * 25) ];
    $userProgress = getUserProgress()[$id] ?? null;
    $materials = collect([
        (object)[
            'title' => 'Pengertian ' . $level->name, 
            'content' => $level->name . " digunakan saat bicara dengan orang yang sederajat atau akrab.\nTidak ada kata krama yang disisipkan. Penggunaan bahasa ini sangat umum dalam kehidupan sehari-hari.", 
            'scenario' => "Budi: Kowe arep menyang endi, Di?\nAndi: Aku arep menyang pasar tuku sayur.\nBudi: Titip sisan, ya. Tukokake tahu."
        ],
    ]);
    return view('levels.show', [
        'level' => $level,
        'userProgress' => $userProgress,
        'materials' => $materials,
    ]);
})->name('levels.show');

Route::get('/levels/{id}/quiz', function ($id) {
    $levelNames = [1 => 'Ngoko Lugu', 2 => 'Ngoko Alus', 3 => 'Krama Lugu', 4 => 'Krama Alus', 5 => 'Evaluasi'];
    $level = (object)[ 'id' => $id, 'level_number' => $id, 'name' => $levelNames[$id] ?? 'Kuis' ];
    $quizzes = collect([
        (object)[
            'id' => 1, 'question_type' => 'mc', 'question_text' => 'Bahasa apa yang paling tepat digunakan ketika berbicara dengan teman sebaya yang sudah akrab?',
            'options' => [ (object)['id'=>1, 'option_text'=>'Ngoko Lugu'], (object)['id'=>2, 'option_text'=>'Krama Lugu'], (object)['id'=>3, 'option_text'=>'Krama Alus'], (object)['id'=>4, 'option_text'=>'Ngoko Alus'] ]
        ],
        (object)[
            'id' => 2, 'question_type' => 'arrange', 'question_text' => 'Susun kata di bawah ini menjadi kalimat yang benar:',
            'options' => [ (object)['id'=>1, 'option_text'=>'Aku'], (object)['id'=>2, 'option_text'=>'arep'], (object)['id'=>3, 'option_text'=>'menyang'], (object)['id'=>4, 'option_text'=>'pasar'] ]
        ],
        (object)[
            'id' => 3, 'question_type' => 'fill_blank', 'question_text' => 'Lengkapilah kalimat berikut: "Bapak nembe ... buku ing ruang tamu."',
            'options' => [] // Fill in the blank doesn't need predefined options in the DB for the form
        ]
    ]);
    return view('levels.quiz', [
        'level' => $level,
        'quizzes' => $quizzes
    ]);
})->name('levels.quiz');

Route::post('/levels/{id}/quiz', function ($id) {
    $userProgress = getUserProgress();
    if (isset($userProgress[$id])) {
        $userProgress[$id] = (object) $userProgress[$id];
        $userProgress[$id]->status = 'completed';
        $userProgress[$id]->score = 100;
        $userProgress[$id]->xp_earned = 50 + (($id - 1) * 25);
        if ($id < 5) {
            $userProgress[$id + 1] = (object) $userProgress[$id + 1];
            $userProgress[$id + 1]->status = 'unlocked';
        }
        session(['userProgress' => $userProgress]);
    }
    return redirect()->route('levels.result', $id);
})->name('levels.quiz.submit');

Route::get('/levels/{id}/result', function ($id) {
    $levelNames = [1 => 'Ngoko Lugu', 2 => 'Ngoko Alus', 3 => 'Krama Lugu', 4 => 'Krama Alus', 5 => 'Evaluasi'];
    $level = (object)[ 'id' => $id, 'level_number' => $id, 'name' => $levelNames[$id] ?? 'Materi' ];
    $nextLevel = $id < 5 ? (object)[ 'id' => $id + 1, 'level_number' => $id + 1, 'name' => $levelNames[$id + 1] ] : null;
    $badge = (object)[ 'name' => 'Lencana Level ' . $id, 'description' => 'Menyelesaikan level dengan baik.' ];
    
    $userProgress = getUserProgress()[$id] ?? null;
    
    return view('levels.result', [
        'level' => $level,
        'passed' => true,
        'score' => $userProgress->score ?? 100,
        'xpEarned' => $userProgress->xp_earned ?? (50 + (($id - 1) * 25)),
        'nextLevel' => $nextLevel,
        'badge' => $badge
    ]);
})->name('levels.result');
