<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use Illuminate\Http\Request;

class DictionaryController extends Controller
{
    /**
     * Menampilkan halaman pencarian kosa kata dan hasil pencarian.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
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
        } else {
            $query->orderBy('indonesia', 'asc');
        }

        $perPage = 20;
        $words = $query->paginate($perPage)->withQueryString();
        $totalWords = Dictionary::count();

        // Kosa kata populer / rekomendasi pencarian cepat
        $quickKeywords = [
            'Makan', 'Tidur', 'Pergi', 'Rumah', 'Bicara', 'Melihat', 
            'Minum', 'Beli', 'Datang', 'Nama', 'Ibu', 'Ayah'
        ];

        if ($request->wantsJson() || $request->ajax() || $request->boolean('json')) {
            return response()->json([
                'success' => true,
                'data' => $words->items(),
                'total' => $words->total(),
                'current_page' => $words->currentPage(),
                'last_page' => $words->lastPage(),
                'first_item' => $words->firstItem() ?? 0,
                'last_item' => $words->lastItem() ?? 0,
                'has_pages' => $words->hasPages(),
                'prev_page_url' => $words->previousPageUrl(),
                'next_page_url' => $words->nextPageUrl(),
                'q' => $q,
                'category' => $category,
            ]);
        }

        return view('dictionary.index', [
            'words' => $words,
            'q' => $q,
            'category' => $category,
            'totalWords' => $totalWords,
            'quickKeywords' => $quickKeywords,
        ]);
    }

    /**
     * Method opsional untuk endpoint pencarian via AJAX / API.
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
