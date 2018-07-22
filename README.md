# Money work example

## Setup

Use built-in php server. 
```bash
php -S 127.0.0.1:8080 -t public public/index.php
```

Or you can use your server.

## Database

Mysql dump in file `migrations/v1.sql`

## Configs

Just create file `config/config_local.php`. Copy data from `config/config.php`. And fill your config data.

For test environment you can create `config/config_local_test.php` with same structure.