# OAuth 2.0 Server

Simple OAuth 2.0 Server based on Thephpleague and Slim framework. 
 
Supported Grants:

- [Password Grant](https://oauth2.thephpleague.com/authorization-server/resource-owner-password-credentials-grant/)
- [Refresh Token Grant](https://oauth2.thephpleague.com/authorization-server/refresh-token-grant/)

## Install Dev (Docker)

1. Set environments with te following examples files
```bash
cp _env/php.example.env  php.env
cp _env/postgres.example.env  postgres.env
```

Encryption key generate: `php -r 'echo base64_encode(random_bytes(32)), PHP_EOL;'`


2. Install backend framework and dependencies
```bash
docker-compose run --rm php composer install
```

3. Generate keys

```bash
mkdir -p var/keys

openssl genrsa -out var/keys/private.key
openssl rsa -in var/keys/private.key -pubout -out var/keys/public.key

chmod 600 var/keys/private.key
chmod 600 var/keys/public.key
```

4. Run migrations

```bash 
docker-compose exec php php cli-migrations.php migrate
```

5. Create client

```bash 
docker-compose exec php php cli.php create-client "Test Client" "secret" "http://127.0.0.1" --confidential
```

6. Create user

```bash 
docker-compose exec php php cli.php create-user "test@example.com" "Test User Name" "secret"
```

## PHP CS Fixer

```bash
docker-compose exec php bash
PHP_CS_FIXER_IGNORE_ENV=8.2.3 php ./vendor/bin/php-cs-fixer fix --diff --dry-run --config .php-cs-fixer.php --verbose
```

## Build prod image

```bash
docker buildx build -t <your_registry>/oauth-server:<version> . --platform=linux/arm64,linux/amd64 -f _docker/php/prod/Dockerfile --push
```

Note:
- The prod image already contains a pre-made var/keys folder, but not the keys. They must be created using the method mentioned above.

Docker-compose example for prod image
```yaml
version: "3.9"
services:
  php_prod:
    image: oauth2-server-prod-image
    env_file:
      - _env/php.env
    restart: unless-stopped
    volumes:
      - ./var/keys:/app/var/keys
    ports:
      - "8888:8888"
    command: php -S 0.0.0.0:8888 -t /app/public/
```
