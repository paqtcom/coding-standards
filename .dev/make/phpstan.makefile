php-phpstan:tools/phpstan/vendor/bin/phpstan
	tools/phpstan/vendor/bin/phpstan analyse src tests --autoload-file vendor/autoload.php --level 8

tools/phpstan/vendor/bin/phpstan: tools
	rm -rf tools/phpstan
	mkdir tools/phpstan
	cd tools/phpstan && composer init --name=paqt/phpstan --no-interaction && composer require --dev --with-all-dependencies phpstan/phpstan:1.3.3 && cd ../..
