## Setup

#### Environment Versions

The versions of the environment where app is developed and tested:

 - Ubuntu 22.04.1 LTS
 - Docker 220.10.23
 - Docker compose 1.15.1

#### Set up

 - docker compose up -d
 - sudo docker exec -it REVENDO_php sh
   - php bin/console doctrine:migrations:migrate
   - bin/console hautelook:fixtures:load
   - php bin/console fos:elastica:populate
 - GO TO: http://localhost:5002/
 - thats it, enjoy!
