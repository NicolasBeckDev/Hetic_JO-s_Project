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

    
    git clone https://github.com/NicolasBeck/Hetic_JO-s_Project
    cd Hetic_JO-s_Project/symfony
    composer install
    # Accepts default parameters but enter "db" for database_host

Create new copie of docker/.env.dist named docker/.env and delete Symfony part.

    yarn install
    cd ../docker
    docker-compose build
    

## Docker run

1 - start your docker soft
2 - exec the next lines

    
    cd docker
    docker-compose up -d
    

## Docker stop

    
    docker-compose stop
    

## Symfony server run

    
    cd symfony
    php bin/console server:run
    

## Symfony server stop

    
    Ctrl+c
    

## Compile assets

    
    yarn encore dev
    
