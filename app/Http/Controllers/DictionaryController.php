<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    /**
     * Helper untuk mengambil data dictionary berdasarkan filter pencarian dan kategori.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $perPage
     * @return array
     */
    protected function getDictionaryData(Request $request, int $perPage = 20)
    {
        $q = trim($request->input('q', ''));
        $category = $request->input('category', 'all');

        $query = Dictionary::query();

        if ($q !== '') {
            if ($category === 'indonesia') {
                $query->where('indonesia', 'like', "%{$q}%");
            } elseif ($category === 'krama_alus') {
                $query->where('krama_alus', 'like', "%{$q}%");
            } elseif ($category === 'krama_inggil') {
                $query->where('krama_inggil', 'like', "%{$q}%");
            } elseif ($category === 'ngoko') {
                $query->where('ngoko', 'like', "%{$q}%");
            } else {
                // Default 'all': fleksibel mencari di semua kolom
                $query->where(function ($sub) use ($q) {
                    $sub->where('indonesia', 'like', "%{$q}%")
                        ->orWhere('krama_alus', 'like', "%{$q}%")
                        ->orWhere('krama_inggil', 'like', "%{$q}%")
                        ->orWhere('ngoko', 'like', "%{$q}%");
                });
            }

            // Prioritaskan hasil yang diawali kata kunci pencarian
            $query->orderByRaw("
                CASE 
                    WHEN LOWER(indonesia) = LOWER(?) THEN 0
                    WHEN LOWER(ngoko) = LOWER(?) THEN 1
                    WHEN LOWER(krama_alus) = LOWER(?) THEN 2
                    WHEN LOWER(krama_inggil) = LOWER(?) THEN 3
                    WHEN LOWER(indonesia) LIKE LOWER(?) THEN 4
                    WHEN LOWER(ngoko) LIKE LOWER(?) THEN 5
                    ELSE 6
                END ASC
            ", [$q, $q, $q, $q, "{$q}%", "{$q}%"])
            ->orderBy('indonesia', 'asc');

            $words = $query->paginate($perPage)->withQueryString();
        } else {
            // Jangan memuat daftar kosa kata secara default agar tampilan tidak memakan ruang
            $words = new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }
        $totalWords = Dictionary::count();

        // Kosa kata populer / rekomendasi pencarian cepat
        $quickKeywords = [
            'Makan', 'Tidur', 'Pergi', 'Rumah', 'Bicara', 'Melihat', 
            'Minum', 'Beli', 'Datang', 'Nama', 'Ibu', 'Ayah'
        ];

        return [
            'words' => $words,
            'q' => $q,
            'category' => $category,
            'totalWords' => $totalWords,
            'quickKeywords' => $quickKeywords,
        ];
    }

    /**
     * Format response JSON standar untuk request AJAX / API.
     *
     * @param  array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonDictionaryResponse(array $data)
    {
        return response()->json([
            'success' => true,
            'data' => $data['words']->items(),
            'total' => $data['words']->total(),
            'current_page' => $data['words']->currentPage(),
            'last_page' => $data['words']->lastPage(),
            'first_item' => $data['words']->firstItem() ?? 0,
            'last_item' => $data['words']->lastItem() ?? 0,
            'has_pages' => $data['words']->hasPages(),
            'prev_page_url' => $data['words']->previousPageUrl(),
            'next_page_url' => $data['words']->nextPageUrl(),
            'q' => $data['q'],
            'category' => $data['category'],
        ]);
    }

    /**
     * Tampilan awal (landing page) Kramain yang menyatu dengan Kamus Bahasa Jawa di halaman depan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function welcome(Request $request)
    {
        $data = $this->getDictionaryData($request);

        if ($request->wantsJson() || $request->ajax() || $request->boolean('json')) {
            return $this->jsonDictionaryResponse($data);
        }

        return view('welcome', $data);
    }

    /**
     * Menampilkan halaman pencarian kosa kata atau redirect ke halaman depan satu page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $data = $this->getDictionaryData($request);

        if ($request->wantsJson() || $request->ajax() || $request->boolean('json')) {
            return $this->jsonDictionaryResponse($data);
        }

        // Arahkan ke halaman depan dengan anchor #kamus agar satu page dengan tampilan awal
        $queryParams = $request->query();
        $redirectUrl = route('welcome', $queryParams) . '#kamus';
        return redirect($redirectUrl);
    }

    /**
     * Method endpoint pencarian via AJAX / API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        $category = $request->input('category', 'all');

        $query = Dictionary::query();

        if ($q !== '') {
            if ($category === 'indonesia') {
                $query->where('indonesia', 'like', "%{$q}%");
            } elseif ($category === 'krama_alus') {
                $query->where('krama_alus', 'like', "%{$q}%");
            } elseif ($category === 'krama_inggil') {
                $query->where('krama_inggil', 'like', "%{$q}%");
            } elseif ($category === 'ngoko') {
                $query->where('ngoko', 'like', "%{$q}%");
            } else {
                $query->where(function ($sub) use ($q) {
                    $sub->where('indonesia', 'like', "%{$q}%")
                        ->orWhere('krama_alus', 'like', "%{$q}%")
                        ->orWhere('krama_inggil', 'like', "%{$q}%")
                        ->orWhere('ngoko', 'like', "%{$q}%");
                });
            }

            $query->orderByRaw("
                CASE 
                    WHEN LOWER(indonesia) = LOWER(?) THEN 0
                    WHEN LOWER(ngoko) = LOWER(?) THEN 1
                    WHEN LOWER(krama_alus) = LOWER(?) THEN 2
                    WHEN LOWER(krama_inggil) = LOWER(?) THEN 3
                    WHEN LOWER(indonesia) LIKE LOWER(?) THEN 4
                    WHEN LOWER(ngoko) LIKE LOWER(?) THEN 5
                    ELSE 6
                END ASC
            ", [$q, $q, $q, $q, "{$q}%", "{$q}%"])
            ->orderBy('indonesia', 'asc');
        } else {
            $query->orderBy('indonesia', 'asc');
        }

        $results = $query->limit(20)->get();

        return response()->json([
            'success' => true,
            'results' => $results,
            'data' => $results,
            'total' => $results->count(),
        ]);
    }
}

