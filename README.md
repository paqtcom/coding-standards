# PAQT Coding Standards
Bevat files voor de coding standaarden die gehanteerd worden binnen Paqt. Het bevat coding standaarden voor [phpstan](https://github.com/phpstan/phpstan), [phpmd](https://github.com/phpmd/phpmd), [phpcs](https://github.com/squizlabs/PHP_CodeSniffer) en [php cs fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer).

## Installeren
Voeg de dependency toe als een (dev) dependency via Composer.
```bash
composer require paqtcom/coding-standards --dev
```

Alle benodigde dependencies worden daarmee geïnstalleerd en hoeven niet apart worden toegevoegd als dependencies
van je project.

Alle dependencies zijn compatible met PHP-versies `7.3` t/m `8.1` waarbij Composer de meest geschikte specifieke versie
van een dependency installeert op basis van de gebruikte PHP-versie en compatibiliteit met andere dependencies van je project.
Composer zal in alle gevallen kiezen voor de hoogst mogelijke geschikte versie van een dependency. 

### Specifieke versie vastzetten
Wanneer er een conflict of incompatibiliteit ontstaat, is het mogelijk om handmatig een specifieke versie van
een dependency te installeren:

```shell
composer require nunomaduro/larastan "^0.7" --update-with-dependencies --dev
```

De betreffende dependency zal dan lokaal voor je project gedowngrade worden en zal verder geen effect hebben
op de werking of configuratie van de `paqtcom/coding-standards` package.

## Configuratie

### phpcs

Voeg een `phpcs.xml` bestand toe aan je project met de volgende minimale configuratie:
```xml
<?xml version="1.0"?>
<ruleset name="Paqt.com phpcs configuration">
    <description>Paqt ruleset for PHP projects.</description>

    <file>app</file>
    <file>src</file>
    ...

    <rule ref="vendor/paqtcom/coding-standards/rules/phpcs.xml" />
</ruleset>
```

```shell
vendor/bin/phpcs -ps
```

Veel van de beschikbare [phpcs configuratie](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Annotated-Ruleset) kan worden
gebruikt voor onder andere het toevoegen van mappen die gescand of genegeerd moeten worden of wanneer voorgedefinieerde
regels aangepast of genegeerd moeten worden:

```xml
<rule ref="vendor/paqtcom/coding-standards/rules/phpcs.xml" />

<rule ref="PSR1.Classes.ClassDeclaration.MissingNamespace">
    <exclude-pattern>database/*</exclude-pattern>
</rule>
```

### php-cs-fixer

Voeg een `.php-cs-fixer.php` bestand toe aan je project met de volgende minimale configuratie:
```php
<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->path([
        '/^app/',
        '/^src/',
        ...
    ]);

/** @var PhpCsFixer\Config $config */
$config = include 'vendor/paqtcom/coding-standards/rules/php-cs-fixer.php';

return $config->setFinder($finder);
```

```shell
vendor/bin/php-cs-fixer fix --dry-run --diff --show-progress=dots --verbose
```

Veel van de beschikbare [php-cs-fixer regels](https://mlocati.github.io/php-cs-fixer-configurator/) kunnen worden gebruikt
om de voorgedefinieerde regels aan te passen of extra regels toe te voegen:

```php
return $config->setFinder($finder)
    ->setRules(
        array_merge($config->getRules(), [
            'no_space_around_double_colon' => false,
        ])
    );
```

> **Let op**: het gebruik van `array_merge($config->getRules(), [])` is nodig om de voorgedefinieerde regels aan te vullen.

### phpmd

Voeg een `phpmd.xml` bestand toe aan je project met de volgende minimale configuratie:
```xml
<?xml version="1.0"?>
<ruleset name="Paqt.com phpmd configuration"
         xmlns="http://pmd.sf.net/ruleset/1.0.0"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://pmd.sf.net/ruleset/1.0.0 http://pmd.sf.net/ruleset_xml_schema.xsd"
         xsi:noNamespaceSchemaLocation=" http://pmd.sf.net/ruleset_xml_schema.xsd">

    <rule ref="vendor/paqtcom/coding-standards/rules/phpmd.xml" />
</ruleset>
```

```shell
vendor/bin/phpmd app src ... phpmd.xml
```
Veel van de beschikbare [phpmd regels](https://phpmd.org/rules/index.html) kunnen worden gebruikt in de
[configuratie](https://phpmd.org/documentation/creating-a-ruleset.html#adding-rule-references-to-the-new-ruleset-xml-file)
om bijvoorbeeld de voorgedefinieerde regels aan te passen of extra regels toe te voegen:

```xml
<rule ref="vendor/paqtcom/coding-standards/rules/phpmd.xml" />

<rule ref="rulesets/codesize.xml/CyclomaticComplexity">
    <priority>1</priority>
    <properties>
        <property name="reportLevel" value="5" />
    </properties>
</rule>

<rule ref="rulesets/naming.xml">
    <exclude name="LongVariable" />
</rule>
```

### phpstan

Voeg een `phpstan.neon` bestand toe aan je project met de volgende minimale configuratie:
```neon
includes:
    - ./vendor/paqtcom/coding-standards/rules/phpstan.neon

parameters:
    paths:
        - app
        - src
        ...
```

Of wanneer het gaat om een Laravel project:
```neon
includes:
    - ./vendor/paqtcom/coding-standards/rules/larastan.neon

parameters:
    paths:
        - app
        - src
        ...
```

```shell
vendor/bin/phpstan analyze --memory-limit=2G
```

Veel van de beschikbare [phpstan configuratie](https://phpstan.org/config-reference) kan worden gebruikt voor het aanpassen
van onder andere voorgedefinieerde regels of eventueel errors die genegeerd mogen worden:

```neon
parameters:
    ignoreErrors:
        - '#Parameter \#3 \$count of method Illuminate\\Database\\Eloquent\\Builder(.*)::has\(\) expects int, Illuminate\\Database\\Query\\Expression given.#'

    checkUnionTypes: false
```

### phpcpd

```shell
vendor/bin/phpcpd app src ...
```

> Voor het gebruik van `phpcpd` is geen configuratie nodig.
