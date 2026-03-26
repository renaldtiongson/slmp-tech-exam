<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\UserApi;
use App\Models\Address;
use App\Models\Geo;
use App\Models\Company;
use Throwable;

class FetchUsersCommand extends Command
{
    protected $signature = 'jsonplaceholder:fetch-users {--fresh}';
    protected $description = 'Fetch users';

    public function handle(): int
    {
        $url = 'https://jsonplaceholder.typicode.com/users';

        if ($this->option('fresh')) {
            Company::query()->delete();
            Geo::query()->delete();
            Address::query()->delete();
            UserApi::query()->delete();
            $this->warn('Existing users deleted.');
        }

        try {
            $response = Http::get($url);

            if (! $response->successful()) {
                $this->error('Failed to fetch');
                return self::FAILURE;
            }

            $users = $response->json();

            foreach ($users as $user) {
                $userApi = UserApi::updateOrCreate(
                    ['source_id' => $user['id']],
                    [
                        'name' => $user['name'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'phone' => $user['phone'] ?? null,
                        'website' => $user['website'] ?? null,
                    ]
                );

                $address = Address::updateOrCreate(
                    ['user_api_id' => $userApi->id],
                    [
                        'street' => $user['address']['street'] ?? '',
                        'suite' => $user['address']['suite'] ?? null,
                        'city' => $user['address']['city'] ?? '',
                        'zipcode' => $user['address']['zipcode'] ?? null,
                    ]
                );

                Geo::updateOrCreate(
                    ['address_id' => $address->id],
                    [
                        'lat' => $user['address']['geo']['lat'] ?? null,
                        'lng' => $user['address']['geo']['lng'] ?? null,
                    ]
                );

                Company::updateOrCreate(
                    ['user_api_id' => $userApi->id],
                    [
                        'name' => $user['company']['name'] ?? '',
                        'catch_phrase' => $user['company']['catchPhrase'] ?? null,
                        'bs' => $user['company']['bs'] ?? null,
                    ]
                );
            }

            $this->info('Done');

            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}