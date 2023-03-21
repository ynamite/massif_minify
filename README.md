# massif_minify

Package für REDAXO CMS >= 5.9.0

Mit diesem AddOn lassen sich mehreren JS/CSS/SCSS Dateien zu einer,
pro Format, einzigen Datei zusammenschliessen um HTTP Request zu minimieren.
Zusätzlich wird nach Wunsch der gesamte HTML Code komprimiert.

Setzt scsshp von Leafo voraus (im System-Addon be_style enthalten)
https://github.com/leafo/scssphp

Dieses Addon basiert in grossen Teilen auf seo42 bzw. dem resource_includer von Starlord
https://github.com/starlord/seo42

## Funktionsliste

-   Kombinieren von mehreren JS/CSS/SCSS Dateien zu einer einzigen Datei um HTTP Request zu minimieren
-   Versions-String Mechanismus damit trotz Caching immer die neuste Version einer JS/CSS Datei heruntergeladen wird
-   Integriert den Redaxo SCSS (SASS) Compiler
-   Automatische Neukompilierung sowie Neukombinierung der Dateien bei Änderungen der Quell-Dateien
-   Einbindung von JavaScript Code aus einem REDAXO Template (oder einer Datei) heraus inkl. PHP Interpretierung

## Installation

-   Release herunterladen und entpacken.
-   In den REDAXO 5 AddOnordner legen /redaxo/src/addons/ oder über das REDAXO Backend installieren und aktivieren
-   Im massif_minify die Pfade eintragen und gewünschten Einstellungen vornehmen
-   In den gewünschten Templates die gewünschte Addon Methode aufrufen, siehe Beispiele unter "Anleitung" im Addon
-   falls es Probleme mit MIME-Types o.ä. gibt, bzw. die CSS/JS Dateien beim Aufruf der Seite nicht geladen/gefunden werden können, kann man versuchen eine Rewrite Base in der .htaccess Datei zu setzen:

```
<IfModule mod_rewrite.c>
RewriteBase /
</IfModule mod_rewrite.c>
```

## Bugs & Feature-Requests

Da es sich mehr um eine Portierung handelt dürfte das Addon weitaus bugfrei sein. Falls trotzdem was auffällt, bitte auf GitHub Issue posten.

Grundsätzlich habe ich das Addon für eigene Zwecke geschrieben und möchte den Code überschaubar halten, daher ist eher nicht davon auszugehen, dass neue Features eingebaut werden. Trotzdem, sinnvolle Inputs, gute Ideen etc. sind gerne willkommen.

## Last Changes

### Version 1.3.3 // 21.03.2023

-   Updated vendors
-   improved PHP >8.2 compatibility

### Version 1.3.2 // 15.12.2021

-   added yrewrite multidomain capability
-   improved PHP 8 compatibility

### Version 1.3.1 // 18.11.2021

-   fixed min be_style requirements
-   Updated vendors

### Version 1.3.0 // 05.02.2020

-   requirements raised to Redaxo >=5.9.0
-   Fixed class namespace for ScssPhp

### Version 1.2.4-Hotfix // 15.02.2019

-   Hotfix for JS single line concatenation

### Version 1.2.3 // 14.12.2018

-   fixed minified inline JS content -> erstmal deaktiviert bis eine funktionierende Alternative gefunden wird
-   getCSSCodeFromTemplate hinzugefügt -> getCSSCodeFromTemplate($templateId, $simpleMinify = true); Danke für den Code Danke @fietstouring
-   Config Option hinzugefügt für Absolute Pfade
-   Minify Vendor Update auf 1.3.61

### Version 1.2.2 // 12.04.2017

-   fixed rex_dir, nicht mehr rekursiv

### Version 1.2.1 // 11.04.2017

-   more bugfixing ftw

### Version 1.2.0 // 10.04.2017

-   Feature: Output-Pfade können separat festgelegt werden
-   Feature: bei der Komprimierung kann gewählt werden, ob auf 1 Zeile komprimiert wird oder nicht
-   div. Bugfixes
-   Vendor update

### Version 1.1.2 // 07.04.2017

-   zusätzliche Funktionen aus resource includer übernommen
-   Bugs mit getCombinedCSSMinFile und getCombinedJSMinFile gefixt (danke @fietstouring & @gut8er)

### Version 1.0.0 // 14.10.2016

-   Bugfixes & Release

### Version 0.0.9 // 14.10.2016

-   Portierung zu Redaxo 5
