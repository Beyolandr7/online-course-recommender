<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseDatasetSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/gabungan_dataset_baru_level_terisi_updated_dropped.csv');

        if (!file_exists($path)) {
            $this->command->error("Dataset tidak ditemukan di: {$path}");
            return;
        }

        $file = fopen($path, 'r');

        $headers = fgetcsv($file);

        if (!$headers) {
            $this->command->error('Dataset kosong atau format CSV salah.');
            return;
        }

        $headers = array_map(function ($header) {
            return strtolower(trim($header));
        }, $headers);

        Course::truncate();

        $total = 0;
        $index = 0;

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) !== count($headers)) {
                $index++;
                continue;
            }

            $data = array_combine($headers, $row);

            Course::create([
                'dataset_index' => $index,
                'title' => $data['course_title'] ?? null,
                'description' => $data['description'] ?? null,
                'skills' => $data['skills'] ?? null,
                'level' => $data['level'] ?? null,
                'url' => $data['url'] ?? null,
                'platform' => $data['platform'] ?? null,
                'combined_features' => $data['combined_features'] ?? null,
            ]);

            $index++;
            $total++;
        }

        fclose($file);

        $this->command->info("Berhasil seed {$total} courses.");
    }
}