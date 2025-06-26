IMAGE_NAME=calmag-app
CONTAINER_NAME=calmag-app

build:
	docker build -t $(IMAGE_NAME) .

run:
	docker run -d --name $(CONTAINER_NAME) -p 8000:8000 $(IMAGE_NAME)

stop:
	docker stop $(CONTAINER_NAME) || true

rm:
	docker rm $(CONTAINER_NAME) || true

logs:
	docker logs -f $(CONTAINER_NAME)

restart: stop rm run

up: build run

down: stop rm
	docker rmi $(IMAGE_NAME) || true

.PHONY: build run stop rm logs restart