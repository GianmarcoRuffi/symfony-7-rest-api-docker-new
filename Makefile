php := php
docker := docker-compose
compose := $(docker) --file docker-compose.yml
docker_exec := $(compose) exec
args = $(filter-out $@,$(MAKECMDGOALS))

test:
	$(docker_exec) $(php) bash -c "./bin/phpunit --color"

testdox:
	$(docker_exec) $(php) bash -c "./bin/phpunit --testdox --color"

up:
	$(docker) up -d
	make install

php-shell:
	$(docker_exec) $(php) bash


db-shell:
	  docker exec -it symfony-7-rest-api-docker-new-db-1 bash

install:
	$(docker_exec) $(php) bash -c "composer install"

coverage:
	$(docker_exec) $(php) bash -c "php -dxdebug.mode=coverage ./bin/phpunit --testdox --color --coverage-html coverage"
.PHONY: coverage

stop:
	$(docker) stop

rm:
	docker rm $(php) --force

rmi:
	docker rmi $$(docker images -q) --force

build:
	$(docker) up -d --build

rebuild: stop rm rmi build

composer:
	$(docker_exec) $(php) composer $(args)