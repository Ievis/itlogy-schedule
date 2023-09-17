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
docker exec -it itlogy_app php /var/www/bin/doctrine orm:schema-tool:create
```

#### 4. Выполнить сиды (fixtures)

```
docker exec -it itlogy_app php /var/www/bin/console users-fixture
docker exec -it itlogy_app php /var/www/bin/console courses-fixture
docker exec -it itlogy_app php /var/www/bin/console schedules-fixture
```

#### Готово

```
localhost:8000 - точка входа в приложение
```