# THANKS https://medium.com/redbubble/running-a-docker-container-as-a-non-root-user-7d2e00f8ee15
docker:
	CURRENT_UID=1000:1000 docker-compose up
lint:
	./vendor/bin/phpstan analyse --memory-limit=-1
format:
	./vendor/bin/php-cs-fixer fix --allow-risky=yes