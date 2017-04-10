massif_minify
================

Package für REDAXO CMS >= 5.0

Mit diesem AddOn lassen sich mehreren JS/CSS/SCSS Dateien zu einer, 
pro Format, einzigen Datei zusammenschliessen um HTTP Request zu minimieren.
Zusätzlich wird nach Wunsch der gesamte HTML Code komprimiert.

Setzt scsshp von Leafo voraus (im System-Addon be_style enthalten)
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


Bugs & Feature-Requests
-------

Da es sich mehr um eine Portierung handelt dürfte das Addon weitaus bugfrei sein. Falls trotzdem was auffällt, bitte auf GitHub Issue posten.

Grundsätzlich habe ich das Addon für eigene Zwecke geschrieben und möchte den Code überschaubar halten, daher ist eher nicht davon auszugehen, dass neue Features eingebaut werden. Trotzdem, sinnvolle Inputs, gute Ideen etc. sind gerne willkommen.


Last Changes
-------

### Version 1.1.3 // 10.04.2017

- Vendor update

### Version 1.1.2 // 07.04.2017

- zusätzliche Funktionen aus resource includer übernommen
- Bugs mit getCombinedCSSMinFile und getCombinedJSMinFile gefixt (danke @fietstouring & @gut8er)


### Version 1.0.0 // 14.10.2016

- Bugfixes & Release

### Version 0.0.9 // 14.10.2016

- Portierung zu Redaxo 5
