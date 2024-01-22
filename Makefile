up:
	docker compose up -d
build:
	docker compose build --no-cache --force-rm

init:
	docker compose up -d --build
	npm install
	docker compose exec php composer install
	docker compose exec php php artisan key:generate
	@make fresh
	@make cache-clear
	composer dump-autoload
remake:
	@make destroy
	@make init
stop:
	docker compose stop
down:
	docker compose down --remove-orphans
down-v:
	docker compose down --remove-orphans --volumes
restart:
	@make down
	@make up
destroy:
	docker compose down --rmi all --volumes --remove-orphans
ps:
	docker compose ps
logs:
	docker compose logs --follow
nginx:
	docker compose exec nginx bash
php:
	docker compose exec php bash
migrate:
	docker compose exec php php artisan migrate
fresh:
	docker compose exec php php artisan migrate:fresh --seed
seed:
	docker compose exec php php artisan db:seed
optimize:
	docker compose exec php php artisan optimize
optimize-clear:
	docker compose exec php php artisan optimize:clear
cache:
	docker compose exec php composer dump-autoload -o
	@make optimize
	docker compose exec php php artisan event:cache
	docker compose exec php php artisan view:cache

cache-clear:
	docker compose exec php composer clear-cache
	@make optimize-clear
	docker compose exec php php artisan cache:clear
	docker compose exec php php artisan event:clear
db:
	docker compose exec db bash
sql:
	docker compose exec db bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'
