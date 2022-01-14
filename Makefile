# THANKS https://medium.com/redbubble/running-a-docker-container-as-a-non-root-user-7d2e00f8ee15
docker:
	CURRENT_UID=1000:1000 docker-compose up
lint:
	./vendor/bin/phpstan analyse --memory-limit=-1
format:
	./vendor/bin/php-cs-fixer fix --allow-risky=yes
test:
	APP_ENV=test php bin/console doctrine:database:drop --force || true
	APP_ENV=test php bin/console doctrine:database:create || true
	sqlite3 ./var/data_test.db ".read ./tools/unit_test/database_sqlite.sql"
	APP_ENV=test php bin/console doctrine:fixtures:load -n
	APP_ENV=test php bin/phpunit