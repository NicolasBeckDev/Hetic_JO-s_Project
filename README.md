# Hetic_JO-s_Project
Web application for Hetic project made with great workers

## Requirement

- php
- git
- yarn
- gitflow
- docker
- (GitKraken)

## Init instruction

    ```bash
    git clone https://github.com/NicolasBeck/Hetic_JO-s_Project
    cd symfony
    composer install
    # Accepts default parameters but enter "db" for database_host
    yarn install
    cd ../docker
    docker-compose build
    ```

## Docker run

1 - start your docker soft
2 - exec the next lines

    ```bash
    cd docker
    docker-compose up -d
    ```

## Docker stop

    ```bash
    docker-compose stop
    ```

## Symfony server run

    ```bash
    cd symfony
    php bin/console server:run
    ```

## Symfony server stop

    ```bash
    Ctrl+c
    ```

## Compile assets

    ```bash
    yarn encore dev
    ```