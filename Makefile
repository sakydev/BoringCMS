# Makefile for Laravel project using Laravel Sail

# Commands
DOCKER_COMPOSE = docker-compose exec
SAIL = $(DOCKER_COMPOSE) laravel

# Targets
.PHONY: help
help:									## Shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.PHONY: up
up:										## Start all containers
	@./vendor/bin/sail up -d

.PHONY: down
down:									## Stop all containers
	@./vendor/bin/sail down

.PHONY: restart
restart:
	@make down
	@make up

.PHONY: clear-cache
clear-cache:							## Clear all types of cache
	@$(SAIL) php artisan config:clear && php artisan route:clear && php artisan cache:clear

.PHONY: database
database:								## Login Postgres container
	@$(DOCKER_COMPOSE) pgsql bash

.PHONY: console
console:								## Login Laravel container
	@$(SAIL) bash

.PHONY: migrate
migrate:								## Run migrations
	@$(SAIL) php artisan migrate

.PHONY: migrate-fresh
migrate-fresh:							## Destroy database and run migrations
	@$(SAIL) php artisan migrate:fresh

.PHONY: delete-merged-branches
delete-merged-branches:					## Delete local merged branches
	git branch --merged | grep -v \* | xargs git branch -D

.PHONY: create_migration
create_migration:						## Create migration inside package
	@$(SAIL) php artisan make:migration $(firstword $(filter-out $@,$(MAKECMDGOALS))) --path=packages/sakydev/boring/database/migrations

.PHONY: create_controller
create_controller:						## Create controller inside package
	@$(SAIL) php artisan make:controller $(firstword $(filter-out $@,$(MAKECMDGOALS))) --path=packages/sakydev/boring/src/Http/Controllers/Api

.PHONY: create_model
create_model:							## Create model inside package
	@$(SAIL) php artisan make:model $(firstword $(filter-out $@,$(MAKECMDGOALS))) --path=packages/sakydev/boring/src/Models


.PHONY: seed
seed:							## Run package seeders
	@$(SAIL) php artisan db:seed  --class=Sakydev\\Boring\\Database\\Seeders\\BoringSeeder

