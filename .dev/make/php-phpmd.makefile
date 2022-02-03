php-phpmd: tools/phpmd-build/vendor/bin/phpmd
	tools/phpmd-build/vendor/bin/phpmd src,tests ansi phpmd.xml

tools/phpmd-build/vendor/bin/phpmd: tools
	rm -rf tools/phpmd-build
	mkdir tools/phpmd-build
	cd tools/phpmd-build && composer init --name=paqt/phpmd --no-interaction && composer require --dev --with-all-dependencies phpmd/phpmd:2.11.1 && cd ../..
