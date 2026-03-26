<?php

namespace App\Console\Commands;

use App\Models\Todo;
use App\Models\UserApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchTodoCommand extends Command
{
    protected $signature = 'jsonplaceholder:fetch-todos {--fresh}';
    protected $description = 'Fetch todos';

    public function handle(): int
    {
        $url = 'https://jsonplaceholder.typicode.com/todos';

        if ($this->option('fresh')) {
            Todo::query()->delete();
            $this->warn('Existing todos deleted.');
        }

        try {
            
            $response = Http::get($url);

            if (! $response->successful()) {
                $this->error('Failed to fetch');
                return self::FAILURE;
            }

            $todos = $response->json();

            foreach ($todos as $todo) {
                $user = UserApi::where('source_id', $todo['userId'])->first();

                if (! $user) {

                    $this->warn("Skipped todo source_id {$todo['id']} - user source_id {$todo['userId']} not found.");
                    continue;
                }

                Todo::updateOrCreate(
                    ['source_id' => $todo['id']],
                    [
                        'user_api_id' => $user->id,
                        'title' => $todo['title'],
                        'completed' => $todo['completed'],
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