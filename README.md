CRUD SAMPLE
===========

setting up database:
```sh
$ docker pull postgres
$ docker run -d \
    --name postgresql \
    -p 5432:5432 \
    -e POSTGRES_PASSWORD=pass \
    postgres

$ PGPASSWORD=pass psql \
    -h 0.0.0.0 \
    -U postgres \
    -d postgres\
    -f data/dump.sql
```

Dependencies:
```sh
composer install
```

Run PHP server:
```sh
$ php -S 0.0.0.0:8080 -t public public/index.php
```
