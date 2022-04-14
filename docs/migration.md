# Migratie
Op dit moment hebben de meeste repositories een eigen set aan coding standard tools als dependencies gedefinieerd in
de `composer.json`. Ook bevatten deze repositories afzonderlijk van elkaar een configuratie met ruleset die gebruikt
moet worden.

De volgende stappen zorgen voor een soepele migratie naar het gebruiken van de PAQT coding standards.

## Installeer `paqtcom/coding-standards` package
De kans is groot dat bepaalde versies van de coding standard tools al geïnstalleerd zijn. Daarom gaan we eerst
een normale installatie uitvoeren van de `paqtcom/coding-standards` package waarbij alleen de nog ontbrekende
dependencies zullen worden geïnstalleerd. Alle benodigde dependencies die al zijn geïnstalleerd, en binnen de versie
range van `paqtcom/coding-standards` package vallen, zullen niet aangepast worden.

Het doel is om ervoor te zorgen dat we de minste afwijkingen in gedrag en resultaat van de tools krijgen, door zoveel
mogelijk de beschikbare versies van dependencies te gebruiken.

```shell
composer require paqtcom/coding-standards --dev
```

## Verwijder overbodige directe dependencies
De `paqtcom/coding-standards` package is nu verantwoordelijk voor de juiste dependencies die nodig zijn voor het
gebruiken van de coding standard tools en dus kunnen de directe dependencies die nog aanwezig zijn in de `composer.json`
verwijderd worden.

```shell
composer remove squizlabs/php_codesniffer friendsofphp/php-cs-fixer phpmd/phpmd phpstan/phpstan nunomaduro/larastan sebastian/phpcpd --dev --no-update
```

## Valideren van de tools
Alle voorgaande aanpassingen zouden geen effect moeten hebben op het gedrag en het resultaat van de coding standard
tools zoals die al gebruikt werden. Dit is dus een goed moment om te checken of alles nog werkt zoals het zou moeten.

## Installeer `paqtcom/paqt-assistant` package
Het is vaak een tijdrovende bezigheid om twee rulesets met elkaar te vergelijken om te zien welke regels er al in de
bovenliggende ruleset staan of welke regels juist een lokale afwijking of toevoeging zijn. Om zo optimaal als mogelijk 
gebruik te maken van de rulesets zoals die in de `paqtcom/coding-standards` package staan is het wenselijk om de
configuraties in de repositories zo 'lean' mogelijk te houden en alleen het minimale wat nodig is erin te zetten.

Om dat proces wat gemakkelijker te maken is er een "PAQT Assistent" ontwikkeld waarmee het onder andere mogelijk is om
verschillen in coding standard tool configuratie te bekijken en wordt er ook een suggestie gegeven voor een mogelijke
configuratie.

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:paqtcom/paqt-assistant.git"
    }
  ]
}
```

```shell
composer require paqtcom/paqt-assistant --dev
```

## Tool configuratie aanpassen
Gebruik de "PAQT Assistent" om de verschillen en configuratie suggestie van de verschillende tools te bekijken en pas
de configuratie van de verschillende tools aan.

```shell
vendor/bin/paqt-assistant coding-standards php-cs-fixer
vendor/bin/paqt-assistant coding-standards phpstan
vendor/bin/paqt-assistant coding-standards phpcs
vendor/bin/paqt-assistant coding-standards phpmd
```

## Valideren van de tools
Nu we ook de configuratie van de verschillende tools hebben aangepast is het weer een goed moment om het gedrag en
resultaat van de coding standard tools te checken. Als alles goed is gegaan gebruiken we nog steeds dezelfde versies van
de dependencies als aan het begin van de migratie dus het gedrag en resultaat zou theoretisch nog exact hetzelfde moeten
zijn.

Indien er wel afwijkingen zijn in het resultaat is het zeer aannemelijk dat dit komt door een wijziging in de
configuratie en/of ruleset en zij er 4 dingen die je kan doen voordat je verder gaat:

1. Er zijn regels aangescherpt en de codebase zal aangepast moeten worden om te voldoen aan deze nieuwe regels. 
2. De wijzigingen aan de configuratie van de betreffende tool ongedaan maken en de "PAQT Assistent" nog een keer raadplegen 
om eventuele verschillen nader te bekijken.
3. Zelf de huidige configuratie in de repository en de configuratie zoals die in de `paqtcom/coding-standards`
package staat met elkaar vergelijken en handmatig een nieuwe configuratie samenstellen.
4. De documentatie van de betreffende tool raadplegen en checken of er wellicht opties in de configuratie gebruikt worden
die niet compatible zijn met de geïnstalleerde versie.

## Upgrade coding standard dependencies
Tot nu toe hebben we de configuratie van de coding standard tools aangepast en verantwoordelijkheid van dependency
management verschoven naar de `paqtcom/coding-standards` package. Omdat het belangrijk is om de dependencies zoveel als
mogelijk up-to-date te houden gaan we nu meteen ook de coding standard dependencies updaten. 

```shell
composer update paqtcom/coding-standards --with-dependencies
```

## Valideren van de tools
Configuraties zijn aangepast en dependencies zijn geupdated en we kunnen nu het eindresultaat bekijken door voor de laatste
keer te checken wat het gedrag en resultaat van de coding standard tools zijn.

Verschillen tussen PHP versies, dependency versies, een aanscherping van de rulesets of een combinatie daarvan, kan er
mogelijk voor zorgen dat er na een upgrade veel meer violations gerapporteerd worden. Op zo'n moment heb je min of meer
de volgende 3 opties:

1. Onderkennen en accepteren dat de ruleset is aangescherpt en de benodigde aanpassingen aan de codebase doorvoeren om 
zo de violations weg te werken.
2. Lokale afwijkingen aan de ruleset definiëren in de configuratie van de betreffende tool.
3. Dependencies downgraden en vastzetten op een specifieke versie om zo bepaalde incompatibiliteit weg te werken.

### _phpstan_ en _larastan_
_phpstan_ en _larastan_ hebben in een relatief korte periode major versies uitgebracht met daarbij ook een paar
aanpassingen die niet altijd backward compatible zijn en voor een grote hoeveelheid violations zorgt.

Met het releasen van `v1.0` van zowel `phpstan/phpstan` als `nunomaduro/larastan` is er een extra level 9 bijgekomen die
strenger checked op het gebruik van `mixed`. Een configuratie met `level: max` kan er dus voor zorgen dat na een upgrade
een grote hoeveelheid violations naar voren komen. In die situatie is het aanpassen van de configuratie naar `level: 8`
voldoende om de violations weg te werken.

Wanneer er na het aanpassen van het level nog steeds een grote hoeveelheid violations gerapporteerd worden die niet
makkelijk aan te passen zijn, kan het zijn dat het downgraden van _phpstan_ of _larastan_ de enige optie is: 

```shell
composer require nunomaduro/larastan "^0.7" --update-with-dependencies --dev
```

## Klaar
Als alles naar wens is en werkt zoals het hoort, is de migratie afgerond. Het enige wat dan nog moet gebeuren is het
verwijderen van de `paqtcom/paqt-assistant` package, aangezien die niet meer nodig is.

De verwijzing naar `git@github.com:paqtcom/paqt-assistant.git` kan uit het `repositories` gedeelte van de `composer.json`
weggehaald worden.

```shell
composer remove paqtcom/paqt-assistant --dev --no-update
```
