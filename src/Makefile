# Makefile for Laravel project using Laravel Sail

.PHONY: up down clear-cache database console

# Commands
DOCKER_COMPOSE = docker-compose exec 
SAIL = $(DOCKER_COMPOSE) laravel.test

# Targets
up:
	@./vendor/bin/sail up -d

down:
	@./vendor/bin/sail down

clear-cache:
	@$(SAIL) php artisan config:clear && php artisan route:clear && php artisan cache:clear

database:
	@$(DOCKER_COMPOSE) pgsql bash

console:
	@$(SAIL) bash
