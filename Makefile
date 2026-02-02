
#---VARIABLES---------------------------------#
#---DOCKER---#
DOCKER = docker
PHP = php
DOCKER_RUN = $(DOCKER) run
DOCKER_COMPOSE = docker compose
PHP_EXEC = $(DOCKER_COMPOSE) exec $(PHP) 
DOCKER_COMPOSE_UP = $(DOCKER_COMPOSE) up -d
DOCKER_COMPOSE_STOP = $(DOCKER_COMPOSE) stop
DOCKER_COMPOSE_DOWN = $(DOCKER_COMPOSE) down
#------------#

#---SYMFONY--#
SYMFONY = symfony
SYMFONY_CONSOLE = $(SYMFONY) console
SYMFONY_LINT = $(SYMFONY_CONSOLE) lint:
#------------#

#---COMPOSER-#
COMPOSER = composer
COMPOSER_INSTALL = $(COMPOSER) install
COMPOSER_UPDATE = $(COMPOSER) update
#------------#




#---PHPUNIT-#
PHPUNIT = APP_ENV=test $(SYMFONY) php bin/phpunit
#------------#
#---------------------------------------------#



## === 🐋  DOCKER ================================================
docker-up: ## Start docker containers.
	$(DOCKER_COMPOSE_UP)
.PHONY: docker-up

docker-stop: ## Stop docker containers.
	$(DOCKER_COMPOSE_STOP)
.PHONY: docker-stop
docker-down: ## Stop and remove docker containers.
	$(DOCKER_COMPOSE_DOWN)
#---------------------------------------------#

## === 🎛️  SYMFONY ===============================================
sf: ## List and Use All Symfony commands (make sf command="commande-name").
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) $(command)"
.PHONY: sf

sf-cc: ## Clear symfony cache.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) cache:clear"
.PHONY: sf-cc

sf-log: ## Show symfony logs.
	$(PHP_EXEC) bash -c "$(SYMFONY) server:log"
.PHONY: sf-log

sf-dc: ## Create symfony database.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) doctrine:database:create --if-not-exists"
.PHONY: sf-dc

sf-dd: ## Drop symfony database.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) doctrine:database:drop --if-exists --force"
.PHONY: sf-dd

sf-su: ## Update symfony schema database.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) doctrine:schema:update --force"
.PHONY: sf-su

sf-mm: ## Make migrations.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) make:migration"
.PHONY: sf-mm

sf-dmm: ## Migrate.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction"
.PHONY: sf-dmm

sf-fixtures: ## Load fixtures.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) doctrine:fixtures:load --no-interaction"
.PHONY: sf-fixtures
sf-user-create: ## Create user.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) make:user"
.PHONY: sf-user-create
sf-me: ## Make symfony entity
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) make:entity"
.PHONY: sf-me

sf-mc: ## Make symfony controller
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) make:controller"
.PHONY: sf-mc

sf-mf: ## Make symfony Form
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) make:form"
.PHONY: sf-mf

sf-perm: ## Fix permissions.
	chmod -R 777 var
.PHONY: sf-perm

sf-sudo-perm: ## Fix permissions with sudo.
	sudo chmod -R 777 var
.PHONY: sf-sudo-perm

sf-dump-env: ## Dump env.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) debug:dotenv"
.PHONY: sf-dump-env

sf-dump-env-container: ## Dump Env container.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) debug:container --env-vars"
.PHONY: sf-dump-env-container

sf-dump-routes: ## Dump routes.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) debug:router"
.PHONY: sf-dump-routes

sf-open: ## Open project in a browser.
	$(PHP_EXEC) bash -c "$(SYMFONY) open:local"
.PHONY: sf-open

sf-open-email: ## Open Email catcher.
	$(PHP_EXEC) bash -c "$(SYMFONY) open:local:webmail"
.PHONY: sf-open-email
sf-auth:#authentication
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) make:auth"
.PHONY: sf-auth

sf-check-requirements: ## Check requirements.
	$(PHP_EXEC) bash -c "$(SYMFONY) check:requirements"
.PHONY: sf-check-requirements
sf-registration:#registration
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) make:registration-form"
.PHONY: sf-registration

sf-show-router: ## Show current route information.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) debug:router"
.PHONY: sf-show-router
sf-mcache: ## Clear symfony cache.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) cache:clear --no-warmup"
.PHONY: sf-mcache
sf-generate-jwt-keys: ## Generate JWT keys.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) lexik:jwt:generate-keypair"
.PHONY: sf-generate-jwt-keys

sf-commands: ## List and Use All Symfony commands (make sf command="commande-name").
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) make:command"	
.PHONY: sf-commands

sf-create-admin: ## Create first admin user.
	$(PHP_EXEC) bash -c "$(SYMFONY_CONSOLE) app:create-admin"
.PHONY: sf-create-admin
#---------------------------------------------#

## === 📦  COMPOSER ==============================================
composer-install: ## Install composer dependencies.
	$(PHP_EXEC) bash -c $(COMPOSER_INSTALL)
.PHONY: composer-install

composer-update: ## Update composer dependencies.
	$(PHP_EXEC) bash -c $(COMPOSER_UPDATE)
.PHONY: composer-update

composer-validate: ## Validate composer.json file.
	$(PHP_EXEC) bash -c "$(COMPOSER) validate"
.PHONY: composer-validate

composer-validate-deep: ## Validate composer.json and composer.lock files in strict mode.
	$(PHP_EXEC) bash -c "$(COMPOSER) validate --strict --check-lock"
.PHONY: composer-validate-deep

composer-req-sec: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require symfony/security-bundle:^7.4"
.PHONY: composer-req-sec

composer-req-twig: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require symfony/twig-bundle:^7.4"
.PHONY: composer-req-twig

composer-req-form: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require symfony/form:^7.4"
.PHONY: composer-req-form

composer-req-maker: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require symfony/maker-bundle --dev"
.PHONY: composer-req-maker

composer-req-validator: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require symfony/validator:^7.4"
.PHONY: composer-req-validator

composer-req-phpunit: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require --dev phpunit/phpunit ^10.0"
.PHONY: composer-req-phpunit
composer-req-profiler: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require --dev symfony/profiler-pack"
.PHONY: composer-req-profiler

composer-req-assets: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require symfony/asset:^7.4"
.PHONY: composer-req-assets
composer-req-translation: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require symfony/translation:^7.4"
.PHONY: composer-req-translation
composer-req-doctrine: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require symfony/orm-pack"
.PHONY: composer-req-doctrine
composer-req-mailer: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require symfony/mailer:^7.4"
.PHONY: composer-req-mailer
composer-req-jwt: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require lexik/jwt-authentication-bundle"
.PHONY: composer-req-jwt
composer-req-http-fn: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require symfony/http-foundation:^7.4"
.PHONY: composer-req-http-fn

composer-req-password-hasher: ## require a package (make composer-req package="package-name").
	$(PHP_EXEC) bash -c "$(COMPOSER) require symfony/password-hasher:^7.4"
.PHONY: composer-req-password-hasher

#---------------------------------------------#


## === 🔎  TESTS =================================================
tests: ## Run tests.
	$(PHPUNIT) --testdox
.PHONY: tests

tests-coverage: ## Run tests with coverage.
	$(PHPUNIT) --coverage-html var/coverage
.PHONY: tests-coverage
#---------------------------------------------#



reset-db: ## Reset database.
	$(eval CONFIRM := $(shell read -p "Are you sure you want to reset the database? [y/N] " CONFIRM && echo $${CONFIRM:-N}))
	@if [ "$(CONFIRM)" = "y" ]; then \
		$(MAKE) sf-dd; \
		$(MAKE) sf-dc; \
		$(MAKE) sf-dmm; \
	fi
.PHONY: reset-db
#---------------------------------------------#