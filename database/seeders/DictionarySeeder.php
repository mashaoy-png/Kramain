<?php

namespace Database\Seeders;

use App\Models\Dictionary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temukan lokasi file CSV
        $possiblePaths = [
            base_path('kamus_jawa_bersih_2.csv'),
            database_path('data/kamus_jawa_bersih_2.csv'),
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            if (File::exists($path)) {
                $filePath = $path;
                break;
            }
        }

        if (!$filePath) {
            $this->command->error("File kamus_jawa_bersih_2.csv tidak ditemukan di direktori proyek!");
            return;
        }

        $this->command->info("Membaca file: {$filePath}");

        // Buka file CSV
        if (($handle = fopen($filePath, 'r')) === false) {
            $this->command->error("Gagal membuka file CSV!");
            return;
        }

        // Kosongkan tabel terlebih dahulu agar tidak duplikat jika di-seed ulang
        Dictionary::truncate();

        // Lewati baris pertama (header: indonesia, krama_alus, krama_inggil, ngoko)
        $header = fgetcsv($handle, 1000, ',');

        $records = [];
        $now = now();
        $count = 0;

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            // Pastikan baris memiliki data minimal untuk kolom indonesia
            if (empty($data) || !isset($data[0]) || trim($data[0]) === '') {
                continue;
            }

            $records[] = [
                'indonesia'    => trim($data[0]),
                'krama_alus'   => isset($data[1]) ? trim($data[1]) : '',
                'krama_inggil' => isset($data[2]) ? trim($data[2]) : '',
                'ngoko'        => isset($data[3]) ? trim($data[3]) : '',
                'created_at'   => $now,
                'updated_at'   => $now,
            ];

            $count++;

            // Insert dalam batch per 100 baris untuk efisiensi memori & performa
            if (count($records) >= 100) {
                Dictionary::insert($records);
                $records = [];
            }
        }

        // Insert sisa data jika ada
        if (!empty($records)) {
            Dictionary::insert($records);
        }

        fclose($handle);

        $this->command->info("Berhasil mengimpor {$count} kosa kata ke tabel dictionaries.");
    }
}
