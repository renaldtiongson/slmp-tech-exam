<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\UserApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchPostsCommand extends Command
{
    protected $signature = 'jsonplaceholder:fetch-posts {--fresh}';
    protected $description = 'Fetch posts';

    public function handle(): int
    {
        $url = 'https://jsonplaceholder.typicode.com/posts';

        if ($this->option('fresh')) {
            Post::query()->delete();
            $this->warn('Existing posts deleted.');
        }


        try {


            $response = Http::get($url);

            if (! $response->successful()) {
                $this->error('Failed to fetch posts.');
                return self::FAILURE;
            }

            $posts = $response->json();

            foreach ($posts as $post) {
                $user = UserApi::where('source_id', $post['userId'])->first();

                if (! $user) {
            
                    $this->warn("Skipped post source_id {$post['id']} - user source_id {$post['userId']} not found.");
                    continue;
                }

                Post::updateOrCreate(
                    ['source_id' => $post['id']],
                    [
                        'user_api_id' => $user->id,
                        'title' => $post['title'],
                        'body' => $post['body'],
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