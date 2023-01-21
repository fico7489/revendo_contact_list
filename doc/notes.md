## Notes on implementation

### project

- docs: https://docs.google.com/document/d/1raB5hti5dX8QBh7IVlbb3A9V6W7iGio1lS9Zz0vvVfY/edit#
- design example: https://www.figma.com/proto/Z2r9eUCCRqdMxhTcYcEUzj/Coding-Interview?node-id=6%3A1085&scaling=min-zoom

### notes on technology used

- code is under the standard with PHP-CS-Fixer -> @Symfony rule
- code is under the standard with PHPStan -> level 9 (which is pretty impressive for me)
- complete instructions for set up app with minimal steps are defined
- elasticsearch data, database and file uploads are split for dev and test
- docker (php, nginx, mysql, mailhog, elasticsearch, kibana)
- 3 doctrine entities (2 relations one-to-one and one-to-many)
- all routes defined with php8 attributes(recommended by symfony)
- all crud actions examples(get, get collection, edit, create, delete)
- 4 symfony forms were used (one with CollectionType)
- uploaded image path is defined in config
- 2 event listeners
- for fixtures nelmio/alice package is used
- elasticsearch is implemented with relative complicated query to search by 4 fields
  (one field is nested) and is all combined with filter for favorites
- 3 tests are created (for api, for backend request, and for listener -> email send)
- basic API Platform api is created with implemented filter for getCollection()


