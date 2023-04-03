build:
	docker-compose build

composer-install:
	docker-compose run --rm boris-bot composer install

composer-update:
	docker-compose run --rm boris-bot composer update