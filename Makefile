SHELL := /bin/bash

tests:
	APP_ENV=test symfony console doctrine:database:drop --force || true
	APP_ENV=test symfony console doctrine:database:create
	APP_ENV=test symfony console doctrine:schema:update --force
	APP_ENV=test symfony console doctrine:fixtures:load -n
	APP_ENV=dev symfony php bin/phpunit $(MAKECMDGOALS)


phpstan:
	APP_ENV=dev symfony php vendor/bin/phpstan analyse --level max --memory-limit=-1

php-cs-fixer:
	APP_ENV=dev symfony php vendor/bin/php-cs-fixer fix


php-cs-fixer-dry-run:
	APP_ENV=dev symfony php vendor/bin/php-cs-fixer fix --dry-run

quality: phpstan php-cs-fixer tests

connect-db:
	docker compose exec database psql app app

async: 
	symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async

cc:
	symfony console c:c

go: 
	symfony server:start -d  


.PHONY: tests

