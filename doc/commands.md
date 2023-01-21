## Commands and resources

### code checks

- vendor/bin/php-cs-fixer fix
- vendor/bin/phpstan analyse
- bin/console lint:twig templates
- bin/phpunit
- curl -GET http://127.0.0.1:9220

### symfony commands

- bin/console doctrine:migrations:diff
- bin/console doctrine:migrations:migrate
- bin/console doctrine:fixtures:load
- bin/console debug:router
- bin/console hautelook:fixtures:load
- bin/console fos:elastica:populate

### docker

- Remove dangling images
    - **docker image prune**
- Show all images:
    - **sudo docker image ls | sort**
- Show all stopped containers
    - **docker ps --filter status="exited" | sort -k 2**
- Show all volumes
    - **docker volume ls**
- Remove container
    - **docker container rm {NAME}**
- Remove volume
    - **docker volume rm {NAME}**

### other

- api platform: https://api-platform.com/
- alice: https://github.com/nelmio/alice
- alice bundle: https://github.com/theofidry/AliceBundle
- faker: https://fakerphp.github.io/formatters/



