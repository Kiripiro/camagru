ifeq (logs,$(firstword $(MAKECMDGOALS)))
  LOGS_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(LOGS_ARGS):;@:)
endif

all: start

start: check_directory
	docker-compose up -d

restart: check_directory
	docker-compose restart

stop:
	docker-compose down

list:
	docker-compose ps

logs:
	docker-compose logs $(LOGS_ARGS)

update:
	git pull origin master

clean:
	docker-compose down -v --rmi all --remove-orphans

fclean: clean
	docker system prune --all --force --volumes
	rm -rf src/Media/posts/*
	find src/Media/avatars -type f -not -name 'avatar.png' -delete

re : fclean all

.PHONY: start restart list logs update stop clean fclean re

check_directory:
	@if [ ! -d "src/Media/posts" ]; then mkdir -p "src/Media/posts"; fi