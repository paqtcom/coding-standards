php-php-cs-fixer: tools/php-cs-fixer-build/vendor/bin/php-cs-fixer
	tools/php-cs-fixer-build/vendor/bin/php-cs-fixer fix src tests --dry-run --diff --allow-risky=yes --config=.php-cs-fixer.php

php-php-cs-fixer-fix: tools/php-cs-fixer-build/vendor/bin/php-cs-fixer
	tools/php-cs-fixer-build/vendor/bin/php-cs-fixer fix src tests --allow-risky=yes --config=.php-cs-fixer.php || true

tools/php-cs-fixer-build/vendor/bin/php-cs-fixer: tools
	rm -rf tools/php-cs-fixer-build
	mkdir tools/php-cs-fixer-build
	cd tools/php-cs-fixer-build && composer init --name=paqt/php-cs-fixer --no-interaction && composer require --dev --with-all-dependencies friendsofphp/php-cs-fixer:3.4.0 && cd ../..
