tools:
	mkdir -p tools
	echo '*' > tools/.gitignore

include .dev/make/php-phpcs.makefile
include .dev/make/php-php-cs-fixer.makefile
include .dev/make/php-phpmd.makefile
include .dev/make/phpstan.makefile

composer:
	composer update

php-test: composer
	php -d pcov.enabled=1 vendor/bin/phpunit tests --coverage-html=build/report --coverage-clover=build/coverage.xml

php-analysis: php-phpstan

php-validate: php-phpcs php-phpmd php-php-cs-fixer

php-fix: php-phpcs-fix php-php-cs-fixer-fix

test: php-test