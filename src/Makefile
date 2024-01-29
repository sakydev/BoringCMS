# Makefile for Laravel project using Laravel Sail

.PHONY: up down clear-cache database console migrate migrate-fresh delete-merged-branches

# Commands
DOCKER_COMPOSE = docker-compose exec 
SAIL = $(DOCKER_COMPOSE) laravel

# Targets
.PHONY: help
help:									## Shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

up:										## Start all containers
	@./vendor/bin/sail up -d

down:									## Stop all containers
	@./vendor/bin/sail down

clear-cache:							## Clear all types of cache
	@$(SAIL) php artisan config:clear && php artisan route:clear && php artisan cache:clear

database:								## Login Postgres container
	@$(DOCKER_COMPOSE) pgsql bash

console:								## Login Laravel container
	@$(SAIL) bash

migrate:								## Run migrations
	@$(SAIL) php artisan migrate

migrate-fresh:							## Destroy database and run migrations
	@$(SAIL) php artisan migrate:fresh

delete-merged-branches:					## Delete local merged branches
	git branch --merged | grep -v \* | xargs git branch -D