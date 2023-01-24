## Setup

#### Environment Versions

The versions of the environment where app is developed and tested:

- Ubuntu 22.04.1 LTS
- Docker 20.10.23
- Docker compose 2.15.1

#### Set up

- git clone https://github.com/fico7489/revendo_contact_list
- cd revendo_contact_list
- docker compose up -d
- docker exec -it REVENDO_php sh
    - composer install
    - bin/console doctrine:migrations:migrate
    - bin/console hautelook:fixtures:load
    - bin/console fos:elastica:populate
- GO TO: localhost:5002
- thats it, enjoy!

### Urls

- App: localhost:5002
- Kibana: localhost:5620
- Mailhog: localhost:8502

### Tests

- docker exec -it REVENDO_php sh
- bin/console doctrine:migrations:migrate --env=test
- bin/phpunit

