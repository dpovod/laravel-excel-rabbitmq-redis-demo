## Развертывание проекта

**1** `cp .env.example .env`

**2** ` docker network create <APP_NETWORK_NAME>` , где `APP_NETWORK_NAME` - значение этой переменной в .env

**3** `docker-compose up -d`

Главная страница будет доступна (после запуска всех сервисов) по URL:

`127.0.0.1:<EXPOSE_HTTP_PORT>`, где `EXPOSE_HTTP_PORT` - значение этой переменной в .env
