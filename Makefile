# THANKS https://medium.com/redbubble/running-a-docker-container-as-a-non-root-user-7d2e00f8ee15

.DEFAULT_GOAL := help
.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: docker
docker: ## start docker
	CURRENT_UID=1000:1000 docker-compose up
.PHONY: lint
lint: ## start linter analyse
	./vendor/bin/phpstan analyse --memory-limit=-1
.PHONY: format
format: ## Format all files using php-cs-fixer
	./vendor/bin/php-cs-fixer fix --allow-risky=yes
.PHONY: php
php: ## connect to php container
	docker-compose exec php bash
.PHONY: pre-test
pre-test: ## initialize DB for unit test
	APP_ENV=test php bin/console doctrine:database:drop --force || true
	APP_ENV=test php bin/console doctrine:database:create || true
	APP_ENV=test php bin/console doctrine:schema:create || true
	APP_ENV=test php bin/console doctrine:fixtures:load -n
.PHONY: test
test: ## launch unit test
	APP_ENV=test php bin/phpunit