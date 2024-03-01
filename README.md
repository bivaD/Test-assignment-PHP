## Test assignment PHP app

You can easily run it using docker. Make sure docker engine is running on your machine.
```
cd Test-assignment-PHP
docker compose up -d
docker compose exec php composer install
```

Set up database
```
docker compose exec php php bin/console make:migration
docker compose exec php php bin/console doctrine:migrations:migrate
```

Symfony server runs on http://127.0.0.1:8000
Vue client runs on http://127.0.0.1:5173

You can generate test data accessing server routes `generator/members` (generates 10 members) and `generator/games` (generates 30 games).

Manual access to database for debugging reasons
```
docker compose exec database bash
mysql --host=localhost --user=root --password=root_password --port=3306 db_name
```
