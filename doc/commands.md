## Commands and resources

### code checks

- vendor/bin/php-cs-fixer fix
- vendor/bin/phpstan analyse
- bin/console lint:twig templates
- bin/phpunit

### symfony commands

- bin/console doctrine:migrations:diff
- bin/console doctrine:migrations:migrate
- bin/console debug:router
- bin/console hautelook:fixtures:load
- bin/console fos:elastica:populate
- composer recipes symfony/twig-bundle

### docker commands

- Remove dangling images
    - **docker image prune**
- Show all images:
    - **docker image ls | sort | grep 'revendo'**
- Show all stopped containers
    - **docker ps --filter status="exited" | sort -k 2**
- Show all stopped containers by name
    - **docker container ls --filter status="exited" | grep 'revendo'**
- Show all volumes
    - **docker volume ls**
- Remove container
    - **docker container rm {NAME}**
- Remove volume
    - **docker volume rm {NAME}**
- Stop all docker containers
    - **docker stop $(docker ps -a -q)**
- Fresh start
    - #stop and remove containers and associated images with common grep search term
    - docker ps -a --no-trunc | grep "revendo" | awk '{print $1}' | xargs -r --no-run-if-empty docker stop && \
      docker ps -a --no-trunc | grep "revendo" | awk '{print $1}' | xargs -r --no-run-if-empty docker rm && \
      docker images --no-trunc | grep "revendo" | awk '{print $3}' | xargs -r --no-run-if-empty docker rmi
    - #remove volumes
    - docker volume rm $(docker volume ls -qf dangling=true)
    - #prune all stopped containers, not used networks, dangling images, build cache
    - docker system prune
### resources

- api platform: https://api-platform.com/
- alice: https://github.com/nelmio/alice
- alice bundle: https://github.com/theofidry/AliceBundle
- faker: https://fakerphp.github.io/formatters/
- elasticsearch bundle: https://github.com/FriendsOfSymfony/FOSElasticaBundle
- docker gists: https://gist.github.com/garystafford/f0bd5f696399d4d7df0f
- bootstrap nice admin: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
