#!/bin/sh

docker exec -it "api" php ./routes/generate-routes.php && ./vendor/bin/php-cs-fixer fix ./routes/routes.php
