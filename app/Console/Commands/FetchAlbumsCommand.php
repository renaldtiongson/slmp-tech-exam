<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\UserApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchAlbumsCommand extends Command
{
    protected $signature = 'jsonplaceholder:fetch-albums {--fresh}';
    protected $description = 'Fetch albums';

    public function handle(): int
    {
        $url = 'https://jsonplaceholder.typicode.com/albums';

        if ($this->option('fresh')) {
            Album::query()->delete();
            $this->warn('Existing albums deleted.');
        }

        try {
            
            $response = Http::get($url);

            if (! $response->successful()) {
                $this->error('Failed to fetch albums.');
                return self::FAILURE;
            }

            $albums = $response->json();


            foreach ($albums as $album) {
                $user = UserApi::where('source_id', $album['userId'])->first();

                if (! $user) {
                    $this->warn("Skipped album source_id {$album['id']} - user source_id {$album['userId']} not found.");
                    continue;
                }

                Album::updateOrCreate(
                    ['source_id' => $album['id']],
                    [
                        'user_api_id' => $user->id,
                        'title' => $album['title'],
                    ]
                );


            }

            $this->info("Done.");
            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}