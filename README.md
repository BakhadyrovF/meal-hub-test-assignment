## Installation

#### Prerequisites:
- [Docker Engine](https://docs.docker.com/engine)
- [Docker Compose](https://docs.docker.com/compose)

1. Copy contents of .env.example to .env file.
You can change values if you want.
```
cp .env.example .env
```
2. Run a shell script to avoid manually entering commands or you can manually run all commands from this file.
```
bash build.bash
```
(*Note that this script is for the first time only*)    
Now you can access app in http://localhost.

## Tests
```
docker compose exec app php artisan test
```

## List of Endpoints
1. **GET** api/users - *Get a lisf of users with their relations*
2. **POST** api/users - *Create a new user*
3. **POST** api/users/to-cart - *Add product to user's cart*
4. **POST** api/users/{id}/orders - *Create order with products from user's cart*
5. **DELETE** api/users/{id} - *Delete user with relations*
6. **POST** api/users/play-secret-santa - *Start game secret-santa with provided user ids*

