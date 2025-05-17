<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PostsImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function collection(Collection $rows)
    {
        $posts = collect();

        foreach ($rows as $row) {
            // Manually insert data into the database
            $posts->push([
                'rowid' => $row['id'],
                'title' => $row['title']
            ]);
        }

        DB::table('posts_index')->insert($posts->toArray());
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}