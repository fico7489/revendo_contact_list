## Setup

#### Environment Versions

The versions of the environment where app is developed and tested:

- Ubuntu 22.04.1 LTS
- Docker 20.10.23
- Docker compose 2.15.1

#### Set up

- docker compose up -d
- sudo docker exec -it REVENDO_php sh
    - bin/console doctrine:migrations:migrate
    - bin/console hautelook:fixtures:load
    - bin/console fos:elastica:populate
- GO TO: http://localhost:5002/
- thats it, enjoy!
