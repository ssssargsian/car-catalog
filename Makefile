ifneq ($(if $(MAKECMDGOALS),$(words $(MAKECMDGOALS)),1),1)
.SUFFIXES:
TARGET := $(if $(findstring :,$(firstword $(MAKECMDGOALS))),,$(firstword $(MAKECMDGOALS)))
PARAMS := $(if $(findstring :,$(firstword $(MAKECMDGOALS))),$(MAKECMDGOALS),$(wordlist 2,100000,$(MAKECMDGOALS)))
.DEFAULT_GOAL = help
.PHONY: ONLY_ONCE
ONLY_ONCE:
	$(MAKE) $(TARGET) COMMAND_ARGS="$(PARAMS)"
%: ONLY_ONCE
	@:
else

DISABLE_XDEBUG=XDEBUG_MODE=off
DCE_API=docker compose exec -it api

.PHONY: help
help: ## Помощь
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: start
start:
	@docker-compose up -d

.PHONY: stop
stop:
	@docker-compose down

.PHONY: composer
composer: ## Работа с Composer. Пример: make -- composer req vendor/package
	@$(DCE_API) sh -c "composer $(COMMAND_ARGS)"

.PHONY: c
c: ## Работа с консолью Symfony. Пример: make -- c c:c
	@$(DCE_API) sh -c "php bin/console $(COMMAND_ARGS)"

.PHONY: ecs
ecs:
	@$(DCE_API) sh -c "vendor/bin/ecs check src --fix"

.PHONY: phpunit
phpunit:
	@echo -n > var/log/test.log
	@docker-compose run --rm api sh -c "$(DISABLE_XDEBUG) php vendor/bin/phpunit --no-coverage $(COMMAND_ARGS)"

.PHONY: cc
cc: ## Очистка кеша
	@$(DCE_API) sh -c "$(DISABLE_XDEBUG) php bin/console c:c $(COMMAND_ARGS)"

.PHONY: dc
dc: ## Удалить контейнеры
	@docker-compose down -v

.PHONY: rmc
rmc:
	@rm -rf ./var/cache
endif
