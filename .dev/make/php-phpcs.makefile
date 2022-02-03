php-phpcs: tools/phpcs-build/vendor/bin/phpcs
	tools/phpcs-build/vendor/bin/phpcs --standard=phpcs.xml --colors -ps

php-phpcs-fix: tools/phpcs-build/vendor/bin/phpcs
	tools/phpcs-build/vendor/bin/phpcbf --standard=phpcs.xml --colors -ps || true

tools/phpcs-build/vendor/bin/phpcs: tools
	rm -rf tools/phpcs-build
	mkdir tools/phpcs-build
	cd tools/phpcs-build && composer init --name=paqt/phpcs --no-interaction && composer require --dev --with-all-dependencies squizlabs/php_codesniffer:3.6.2 && cd ../..
