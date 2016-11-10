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
