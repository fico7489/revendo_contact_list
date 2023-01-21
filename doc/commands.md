## Commands and resources

### code checks
- vendor/bin/php-cs-fixer fix
- vendor/bin/phpstan analyse
- php bin/console lint:twig templates
- bin/phpunit
- curl -GET http://127.0.0.1:9220


### symfony commands
- php bin/console doctrine:migrations:diff
- php bin/console doctrine:migrations:migrate
- php bin/console doctrine:fixtures:load
- php bin/console debug:router
- bin/console hautelook:fixtures:load
- php bin/console fos:elastica:populate


### docker 
- Remove dangling images
   -  **docker image prune**
 - Show all images: 
   - **sudo docker image ls | sort**
 - Show all stopped containers
   - **docker ps --filter status="exited" | sort -k 2**
 - Show all volumes
   - **docker volume ls**
 - Remove container
   - **docker container rm SHA**
 - Remove volume
   - **docker volume rm SHA**



### other
- api platform: https://api-platform.com/
- alice: https://github.com/nelmio/alice
- alice bundle: https://github.com/theofidry/AliceBundle
- faker: https://fakerphp.github.io/formatters/





