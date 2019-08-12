include docker/scripts/env

.PHONY: start stop

help:
	@echo "Graphic Editor"
	@echo "----------------------------------------------------------------------------------------------------"
	@echo "List of available targets:"
	@echo "  install                  - Install dependencies"
	@echo "  start                    - Stars dev containers."
	@echo "  stop                     - Stops dev containers."
	@echo "  down                     - Stops containers and removes containers, networks, volumes, and images"
	@echo "  hijack                   - Interactive console inside the application container"
	@echo "  help                     - Shows this dialog."
	@echo "  test                     - Runs the test suite"
	@exit 0

install: composer-install

composer-install:
	@docker-compose exec $(APP_CONTAINER_NAME) bash ./docker/scripts/composer-install.sh

start:
	@printf "$(INFO_COLOR)==> Initializing the Application $(NO_COLOR)\n"
	@printf "$(INFO_COLOR)==> Starting containers$(NO_COLOR)\n"
	@docker-compose up -d
	@printf "$(INFO_COLOR)==> Installing dependencies if there are any $(NO_COLOR)\n"
	@docker-compose exec $(APP_CONTAINER_NAME) bash ./docker/scripts/composer-install.sh
	@docker exec $(NEO_CONTAINER) /bin/bash -c "cat /migrations/constraints.cypher | bin/cypher-shell"

stop:
	@printf "$(INFO_COLOR)==> Stopping containers$(NO_COLOR)\n"
	@docker-compose stop

down:
	@printf "$(INFO_COLOR)==> Stopping/Removing containers$(NO_COLOR)\n"
	@docker-compose down

hijack:
	@printf "$(INFO_COLOR)==> Hijacking $(APP_CONTAINER_NAME) container$(NO_COLOR)\n"
	@docker-compose exec $(APP_CONTAINER_NAME) bash

test:
	@printf "$(INFO_COLOR)==> Initializing $(APP_CONTAINER_NAME) tests$(NO_COLOR)\n"
	@docker-compose exec $(APP_CONTAINER_NAME) vendor/bin/phpunit

api_docs:
	@printf "$(INFO_COLOR)==> Generating API endpoints documentation$(NO_COLOR)\n"
	${DOCKER_RAML} -o ${WEB_DOCS}/index.html
	@printf "$(OK_COLOR)==> Finished!$(NO_COLOR), API docs are available here: ${WEB_DOCS}/index.html\n"
