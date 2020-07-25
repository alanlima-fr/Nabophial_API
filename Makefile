.DEFAULT_GOAL=help
GROUPS=all
LOCAL_DOMAIN ?= api.nabophial.com
EXEC_PHP = symfony php
$(eval LAST_COMMIT = $(shell git log -1 --oneline --pretty=format:"%h - %an, %ar"))


help:
	@printf "\n                              api-nabophial"
	@printf "\n                              ------------"
	@printf "\n"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$|^##' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
	@#grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%s\033[0m %s\n", $$1, $$2}'
	@#fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
	@printf "\n"
	@printf "\nLast release: \033[32m%s\033[0m" \
	  $(shell git describe --abbrev=0 --tags)
	@printf "\nLast commit: "
	@printf "\033[32m %s\033[0m" \
	  $(LAST_COMMIT)
	@printf "\n"

##
##                                 Setup
##---------------------------------------------------------------------------
##

up: symfony-up vendor## Start project

down: symfony-down docker-down ## Stop project

symfony-up: ## start the project
	symfony proxy:domain:attach $(LOCAL_DOMAIN)
	symfony proxy:start
	symfony server:ca:install
	symfony serve -d

symfony-down: ## stop the project
	symfony server:stop
	symfony proxy:stop

vendor: ## Install dependencies locally
	symfony composer install

##
##                                Quality
##---------------------------------------------------------------------------
##
coverage: vendor ## Execute coverage unit tests
	$(EXEC_PHP) ./vendor/bin/phpunit --coverage-html --coverage-test tests

test: test-unitaire test-fonctionnel coverage ## Execute all test

test-unitaire: vendor ## Execute unit tests
	$(EXEC_PHP) ./vendor/bin/phpunit tests --testdox --colors=always

test-fonctionnel: vendor ## Execute functional tests
	symfony console