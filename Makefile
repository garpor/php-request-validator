SHELL := /bin/bash

# =================================================================
# Targets for Project Management (Docker and Composer)
# =================================================================

.PHONY: test composer build up down

test: ## Runs PHPUnit tests inside the Docker container.
	@echo "Running tests with PHPUnit..."
	docker compose run --rm php-request-validator ./vendor/bin/phpunit $(filter-out $@,$(MAKECMDGOALS))

composer: ## Runs a Composer command inside the container.
	@echo "Running composer..."
	docker compose run --rm php-request-validator composer $(filter-out $@,$(MAKECMDGOALS))

build: ## Builds the app image.
	@echo "Building the app image..."
	docker compose build

up: ## Starts the app service.
	@echo "Starting the app service..."
	docker compose up -d

down: ## Stops the app service.
	@echo "Stopping the app service..."
	docker compose down

# =================================================================
# Target for Help
# =================================================================

.DEFAULT_GOAL := help
help:
	@echo "Usage: make <target>"
	@echo "Available targets:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-25s\033[0m %s\n", $$1, $$2}'
