//Run Following Commands
php artisan migrate
php artisan db:seed --class=UserSeeder

//parse data
php artisan jsonplaceholder:fetch-users --fresh
php artisan jsonplaceholder:fetch-todos --fresh
php artisan jsonplaceholder:fetch-posts --fresh
php artisan jsonplaceholder:fetch-comments --fresh
php artisan jsonplaceholder:fetch-albums --fresh
php artisan jsonplaceholder:fetch-photos --fresh

For Authentication,
POST http://127.0.0.1:8000/api/login
{
  "email": "test@example.com",
  "password": "password123"
}

Result will be:
{
    "token": "5|tokenexamplexxxxxxx"
}

to view data,
GET http://127.0.0.1:8000/api/users
GET http://127.0.0.1:8000/api/todos
GET http://127.0.0.1:8000/api/posts
GET http://127.0.0.1:8000/api/comments
GET http://127.0.0.1:8000/api/albums
GET http://127.0.0.1:8000/api/photos


Example:
GET http://127.0.0.1:8000/api/users
Authorization -> Bearer Token -> tokenexamplexxxxxxx

Header -> Accept application/json

Send


















