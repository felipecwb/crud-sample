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

The `config/bootstrap.php` contains the info for connect to the database.

Dependencies:
```sh
composer install
```

Run PHP server:
```sh
$ php -S 0.0.0.0:8080 -t public public/index.php
```

Using ~ReactJS~ (not really) Copy and Past pattern with JSX
