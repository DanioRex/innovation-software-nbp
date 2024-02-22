start-dev: ## Start the docker hub
	@docker-compose -f docker-compose.dev.yml -f docker-compose.yml up -d --build

stop: ## Stop the docker hub
	@docker-compose down --remove-orphans

install: ## Install Symfony dependencies
	@docker-compose exec app composer install

migrate: ## Migrate the database
	@docker-compose exec app bin/console doctrine:migrations:migrate --no-interaction

fixtures: ## Load fixtures
	@docker-compose exec app bin/console doctrine:fixtures:load --no-interaction

reset-db: migrate fixtures ## Reset the database

test: ## Run PHPUnit Tests
	@docker-compose exec app ./vendor/bin/phpunit

update-currencies-a:
	@docker-compose exec app bin/console app:currency:update:nbp A
