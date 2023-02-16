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

5. Run migrations

```bash 
docker-compose exec php php cli-migrations.php migrate
```

5. Create client

```bash 
docker-compose exec php php cli.php create-client "Test Client" "secret" "http://127.0.0.1" --confidential
```

5. Create user

```bash 
docker-compose exec php php cli.php create-user "test@example.com" "Test User Name" "secret"
```

## PHP CS Fixer

```bash
docker-compose exec php bash
PHP_CS_FIXER_IGNORE_ENV=8.2.3 php ./vendor/bin/php-cs-fixer fix --diff --dry-run --config .php-cs-fixer.php --verbose
```

