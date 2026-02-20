DOCKER_USER    = manfredsteger
IMAGE_NAME     = webklex-calmag
CONTAINER_NAME = calmag-app
VERSION        = $(shell git describe --tags --abbrev=0)
FULL_IMAGE     = $(DOCKER_USER)/$(IMAGE_NAME)

## Lokales Build (nur native Arch, schnell zum Testen)
build:
	docker build -t $(FULL_IMAGE):$(VERSION) -t $(FULL_IMAGE):latest .

## Multi-Arch Build + direkt zu Docker Hub pushen (amd64 + arm64)
release:
	docker buildx build \
		--platform linux/amd64,linux/arm64 \
		--tag $(FULL_IMAGE):$(VERSION) \
		--tag $(FULL_IMAGE):latest \
		--push .

run:
	docker run -d --name $(CONTAINER_NAME) -p 8000:8000 $(FULL_IMAGE):latest

stop:
	docker stop $(CONTAINER_NAME) || true

rm:
	docker rm $(CONTAINER_NAME) || true

logs:
	docker logs -f $(CONTAINER_NAME)

restart: stop rm run

up: build run

## Alles aufräumen (alle lokalen Tags), neu bauen und starten
complete:
	docker rm -f $(CONTAINER_NAME) 2>/dev/null || true
	docker images $(FULL_IMAGE) -q | xargs docker rmi -f 2>/dev/null || true
	docker build -t $(FULL_IMAGE):$(VERSION) -t $(FULL_IMAGE):latest .
	docker run -d --name $(CONTAINER_NAME) -p 8000:8000 $(FULL_IMAGE):latest
	@echo "✅ $(FULL_IMAGE):$(VERSION) läuft auf http://localhost:8000"

down: stop rm
	docker images $(FULL_IMAGE) -q | xargs docker rmi -f 2>/dev/null || true

## Aktuelle Version anzeigen
version:
	@echo "Version: $(VERSION)"

## Hilfe
help:
	@grep -E '^[a-zA-Z_-]+:' Makefile | grep -v '^\s' | awk -F: '{printf "\033[36m%-12s\033[0m\n", $$1}'

.PHONY: build release run stop rm logs restart up down complete version help
