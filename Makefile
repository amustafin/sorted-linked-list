start: stop
	docker compose up -d --build --remove-orphans

stop:
	docker compose down

bash:
	docker compose exec php81 bash

remove-dependencies:
	docker compose exec php81 rm -rf ./vendor
	docker compose exec php81 rm -f ./composer.lock

install-dependencies:
	docker compose exec php81 composer install

reinstall-dependencies: remove-dependencies install-dependencies