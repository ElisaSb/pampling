#!/bin/bash

# Docker containers
DOCKER_BE = pampling_php
DOCKER_DB = pampling_db

# Env files
ENV_LOCAL = .env.local
DEFAULT_ENV = dev
DOCKER_ENV = docker/.env

# Alias
UID = 1000:1000
EXEC_PHP = php
DOCKER_EXEC = U_ID=${UID} docker exec -it -u ${UID}
DOCKER_SSH = ${DOCKER_EXEC} ${DOCKER_BE}
DOCKER_COMPOSE_SSH = docker-compose --env-file ${DOCKER_ENV} exec -T -u ${UID} pampling_php env TERM=xterm
DOCKER_ROOT_SSH = docker exec -it -u root ${DOCKER_BE}

help:
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

install:
	make env-install-unix
	make net-create
	make start
	$(DOCKER_ROOT_SSH) composer install
	$(DOCKER_ROOT_SSH) composer config --global disable-tls true
	$(DOCKER_ROOT_SSH) composer config --global secure-http false
	@echo '¡Instalado! ¡Dirígete a http://127.0.7.14 con tu navegador!'
	@echo 'mysql server: 127.0.0.1:3312'

net-create:
	docker network inspect pampling-network >/dev/null || docker network create pampling-network
	@echo '¡pampling-network activa!'

uninstall:
	make stop
	docker-compose --env-file ${DOCKER_ENV} rm
	docker volume rm -f docker_pampling_dbdata
	@echo '¡Se ha desinstalado!'

env-install-%:
	touch $(DOCKER_ENV)
	cat "docker/.$*.conf" > $(DOCKER_ENV)

start:
	docker-compose --env-file ${DOCKER_ENV} up -d --remove-orphans

stop:
	docker-compose --env-file ${DOCKER_ENV} down --remove-orphans

build:
	docker-compose --env-file ${DOCKER_ENV} build

ssh-be:
	$(DOCKER_SSH) bash

compose-ssh:
	$(DOCKER_COMPOSE_SSH)

mac-setup-ip-aliases:
	sudo ifconfig lo0 alias 127.0.7.14 up
	sudo ifconfig lo0 down && sudo ifconfig lo0 up
	@echo '¡IP Alias creados!'