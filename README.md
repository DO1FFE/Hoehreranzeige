--------------------------------------
[INFUSION] Hörer-Anzeige
Für PHP-Fusion Version 7.x
by Erik Schauer
--------------------------------------

-------------
0. Einleitung
-------------

Hörer-Anzeige für PHP-Fusion Version 7.x
Hier werden aktive Hörer eines Shoutcast-Streams mit Nickname aus PHP-Fusion angezeigt.

BITTE BEACHTEN: Diese Version hat noch BETA-Status.
Fehlfunktionen sind nicht ausgeschlossen!
Ich übernehme keinerlei Haftung bei eventuellen Schäden.

Features:
-Adminpanel mit Datenbankanbindung zum speichern der Einstellungen
-Einstellmöglichkeit der Moderatoren-Gruppe für Zugriff auf Mod-Data
-Einstellmöglichkeit woher DJ-Name bezogen wird (IRC/AIM/ICQ/ServerTitle des Shoutcast)
-Einstellmöglichkeit der IP des Telefonstream-Anbieters (ändert sich ab und zu mal) - www.phonepublisher.com
-Stream-Data (für Admins) und Mod-Data (für Moderatoren) ein- und ausklappbar für jeden Stream (Um Platz zu sparen)
-Stream-Data mit Link zu geografischer Anzeige (Google-Maps) der IP-Adresse eines Hörers und Hostname
-Mod-Data ohne Anzeige der IP und des Hostnamens (Datenschutz) - sonst identisch zu Stream-Data
-Anzeige des verwendeten Programms mit Versionsnummer in Mod-Data und Stream-Data
-Anzeige, wie lange der Hörer schon eingeschaltet hat (Mod-Data und Stream-Data)
-und vieles mehr...

---------------------------------------
1. Installation
---------------------------------------

Inhalt des Ordners "files" muss in das "root"-Verzeichnis
der PHP-Fusion Installation kopiert werden.

Danach kann die Infusion über Administration -> System -> Infusionen
installiert werden.

Nach der Installation befindet sich ein Mittel-Panel auf der Webseite,
welches auf jeder Seite angezeigt wird.
Einstellungen hierzu können in Administration -> System -> Panels
angepasst werden.

Einstellungen zu den Streams können unter Administration -> Infusionen 
vorgenommen werden.

---------------------------------------
3. Changelog
---------------------------------------
v0.2.4-beta Verbesserung Adminpanel (Passwörter als *****, Formularanzeige nur bei Editieren, mehr Einstellungen)
v0.2.3-beta Erweiterung auf mehrere Streams
v0.2.2-beta DJ-Anzeige wer gerade On Air ist mit Avatar / Einstellungen im Admin / Kleinere Verbesserungen
v0.2.1-beta Mod-Data (für Moderatoren) und Stream-Data (für Admins) integriert / Diese readme_de hinzugefügt
v0.2.0-beta Konvertiert als PHP-Fusion Version 7.x Infusion

---------------------------------------
4. Update:
---------------------------------------
v0.2.3-beta => v0.2.4-beta: Hörer-Anzeige deinstallieren, alle Dateien aus dem Paket hochladen (überschreiben) und Hörer-Anzeige neu installieren.
v0.2.2-beta => v0.2.3-beta: Alle Dateien aus dem Paket hochladen (überschreiben)
v0.2.1-beta => v0.2.2-beta: Hörer-Anzeige deinstallieren, alle Dateien aus dem Paket hochladen (überschreiben) und Hörer-Anzeige neu installieren.
v0.2.0-beta => v0.2.1-beta: Hörer-Anzeige deinstallieren, alle Dateien aus dem Paket hochladen (überschreiben) und Hörer-Anzeige neu installieren.
Achtung: Vorhandene Einstellungen werden dabei gelöscht.

---------------------------------------
5. Thanks to:
---------------------------------------
-Myself
