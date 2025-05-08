#make build        Erstellt Images (inkl. vendor)
#make up           Startet alle Container
#make stop         Stoppt Container
#make logs         Zeigt Live-Logs
#make sh           Öffnet Shell in app-Container
#make composer     Zugriff auf Composer im Container
#make clean        Entfernt Container & Volumes (Vorsicht!)


# Name des Docker Compose Projekts (optional)
PROJECT_NAME=myapp

# Standardziel
.PHONY: up
up: build
	docker compose up -d

# Image bauen (inkl. vendor via Multistage)
.PHONY: build
build:
	docker compose build --no-cache

# Container stoppen
.PHONY: stop
stop:
	docker compose down

# Logs live ansehen
.PHONY: logs
logs:
	docker compose logs -f

# Shell-Zugriff auf PHP-Container
.PHONY: sh
sh:
	docker compose exec app sh

# Composer ausführen im Container (z. B. für nachträgliches require)
.PHONY: composer
composer:
	docker compose exec app composer

# Container neu starten
.PHONY: restart
restart:
	docker compose down && docker compose up -d

# Cleanup alles (Vorsicht!)
.PHONY: clean
clean:
	docker compose down -v --remove-orphans
