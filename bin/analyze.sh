#!/usr/bin/sh

php ./vendor/bin/php-cs-fixer fix ./src ./public --config=.php-cs-fixer.dist.php
&& php ./vendor/bin/phpstan analyze ./src ./public --level=5