<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchPhotosCommand extends Command
{
    protected $signature = 'jsonplaceholder:fetch-photos {--fresh}';
    protected $description = 'Fetch photos';

    public function handle(): int
    {
        $url = 'https://jsonplaceholder.typicode.com/photos';

        if ($this->option('fresh')) {
            Photo::query()->delete();
            $this->warn('Existing photos deleted.');
        }

        try {
        
            $response = Http::get($url);

            if (! $response->successful()) {
                $this->error('Failed to fetch photos.');
                return self::FAILURE;
            }

            $photos = $response->json();


            foreach ($photos as $photo) {
                $album = Album::where('source_id', $photo['albumId'])->first();

                if (! $album) {
                    $this->warn("Skipped photo source_id {$photo['id']} - album source_id {$photo['albumId']} not found.");
                    continue;
                }

                Photo::updateOrCreate(
                    ['source_id' => $photo['id']],
                    [
                        'album_id' => $album->id,
                        'title' => $photo['title'],
                        'url' => $photo['url'],
                        'thumbnail_url' => $photo['thumbnailUrl'],
                    ]
                );

            }

            $this->info("Done");
            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}