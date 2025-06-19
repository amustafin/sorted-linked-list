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

codestyle:
	docker compose exec php81 composer codestyle

cs: codestyle

codestyle-fix:
	docker compose exec php81 composer codestyle-fix

cs-fix: codestyle-fix

phpstan:
	docker compose exec php81 composer phpstan

test:
	docker compose exec php81 composer test

verify-dependencies:
	docker compose exec php81 composer audit
	docker compose exec php81 composer verify-dependencies

vd: verify-dependencies

verify: verify-dependencies codestyle phpstan test
