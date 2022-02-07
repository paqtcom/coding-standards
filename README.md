# Paqt coding standards
Bevat tooling en files voor de coding standaarden die gehanteerd worden binnen Paqt. Het bevat coding standaarden voor [phpstan](https://github.com/phpstan/phpstan), [phpmd](https://github.com/phpmd/phpmd), [phpcs](https://github.com/squizlabs/PHP_CodeSniffer) en [php cs fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer).

## Installeren:
Voeg de dependency toe als een (dev) dependency via Composer.
```bash
composer require paqtcom/coding-standards --dev
```

Mocht je de phpstan configuratie automatisch willen updaten, dan dien je symfony/yaml ook te requiren in je composer dependencies.

## Automatisch coding standaard updaten van een project
Dit composer package wordt geïnstalleerd met een reeks executables om vanuit de terminal de coding standaard te updaten. Deze gaan er wel vanuit dat de coding standaard in
het root van het project neergezet moet worden. Sommige configuratiefiles dienen ook in te stellen welke folders er gecheckt moeten worden. Daar moet je dus ook meegeven
welke paden ingesteld moeten worden:

Een voorbeeld makefile waar src en tests ingesteld worden voor phpcs en enkel src voor phpstan:
```makefile
composer:
	composer install
update-coding-standards: composer
	vendor/bin/update-php-cs-fixer
	vendor/bin/update-phpcs src,tests
	vendor/bin/update-phpmd
	vendor/bin/update-phpstan src
```

## Eigen php script maken voor het updaten van een project
Mocht je hier vanaf wijken, dan kan je de coding standaard ook laten updaten via php classes.

```php
<?php
use PaqtCom\CodingStandards\CodingStandardGenerator;

CodingStandardGenerator::updatePhpCsFixerConfig(__DIR__ . '/.dev/default');
```
