### Инструкция по запуску проекта:

#### 1. Установить зависимости

```
composer install
```

#### 2. Поднять контейнеры

```
docker-compose up -d
```

#### 3. Выполнить миграцию схемы бд

```
docker exec -it itlogy_app php /var/www/bin/console db-migrate:up
```

#### 4. Выполнить сиды (fixtures)

```
docker exec -it itlogy_app php /var/www/bin/console user-seed
docker exec -it itlogy_app php /var/www/bin/console course-seed
docker exec -it itlogy_app php /var/www/bin/console schedule-seed
```

#### Готово

```
localhost:8000 - точка входа в приложение
```