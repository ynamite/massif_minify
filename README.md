massif_rewrite
================

Ein Komprimierungs Addon für REDAXO CMS >= 5.0

Mit diesem AddOn lassen sich mehreren JS/CSS/SCSS Dateien zu einer 
pro Format einzigen Datei kombinieren um HTTP Request zu minimieren.
Zusätzlich wird nach Wunsch auch der gesamte HTML Code komprimiert.

Setzt scssphp von Leafo voraus (im Addon be_style enthalten)
https://github.com/leafo/scssphp    

Dieses Addon basiert in grossen Teilen auf seo42 bzw. dem resource_includer von Starlord
https://github.com/starlord/seo42

Funktionsliste
-------

* Kombinieren von mehreren JS/CSS/SCSS Dateien zu einer einzigen Datei um HTTP Request zu minimieren
* Versions-String Mechanismus damit trotz Caching immer die neuste Version einer JS/CSS Datei heruntergeladen wird
* Integrierter SCSS (SASS) Compiler
* Automatische Neukompilierung sowie Neukombinierung der Dateien bei Änderungen der Quell-Dateien
* Einbindung von JavaScript Code aus einem REDAXO Template (oder einer Datei) heraus inkl. PHP Interpretierung

Installation
-------

* Release herunterladen und entpacken.
* In den REDAXO 5 AddOnordner legen /redaxo/src/addons/ oder über das REDAXO Backend installieren und aktivieren
* Im massif_minify die Pfade eintragen und gewünschten Einstellungen vornehmen
* In den gewünschten Templates die gewünschte Addon Methode aufrufen, siehe Beispiele unter "Anleitung" im Addon


Last Changes
-------

### Version 0.0.9 // 14.10.2016

- Erste Version
- Portierung zu Redaxo 5
