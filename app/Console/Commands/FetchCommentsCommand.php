<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchCommentsCommand extends Command
{
    protected $signature = 'jsonplaceholder:fetch-comments {--fresh}';
    protected $description = 'Fetch comments';

    public function handle(): int
    {
        $url = 'https://jsonplaceholder.typicode.com/comments';

        if ($this->option('fresh')) {
            Comment::query()->delete();
            $this->warn('Existing comments deleted.');
        }

        try {
            
            $response = Http::get($url);

            if (! $response->successful()) {
                $this->error('Failed to fetch comments.');
                return self::FAILURE;
            }

            $comments = $response->json();


            foreach ($comments as $comment) {
                $post = Post::where('source_id', $comment['postId'])->first();

                if (! $post) {
                    $this->warn("Skipped comment source_id {$comment['id']} - post source_id {$comment['postId']} not found.");
                    continue;
                }

                Comment::updateOrCreate(
                    ['source_id' => $comment['id']],
                    [
                        'post_id' => $post->id,
                        'name' => $comment['name'],
                        'email' => $comment['email'],
                        'body' => $comment['body'],
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