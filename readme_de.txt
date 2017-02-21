--------------------------------------
[INFUSION] H�rer-Anzeige
F�r PHP-Fusion Version 7.x
by Erik Schauer (http://www.schauer-hosting.de)
--------------------------------------

-------------
0. Einleitung
-------------

H�rer-Anzeige f�r PHP-Fusion Version 7.x
Hier werden aktive H�rer eines Shoutcast-Streams mit Nickname aus PHP-Fusion angezeigt.

BITTE BEACHTEN: Diese Version hat noch BETA-Status.
Fehlfunktionen sind nicht ausgeschlossen!
Ich �bernehme keinerlei Haftung bei eventuellen Sch�den.

Features:
-Adminpanel mit Datenbankanbindung zum speichern der Einstellungen
-Einstellm�glichkeit der Moderatoren-Gruppe f�r Zugriff auf Mod-Data
-Einstellm�glichkeit woher DJ-Name bezogen wird (IRC/AIM/ICQ/ServerTitle des Shoutcast)
-Einstellm�glichkeit der IP des Telefonstream-Anbieters (�ndert sich ab und zu mal) - www.phonepublisher.com
-Stream-Data (f�r Admins) und Mod-Data (f�r Moderatoren) ein- und ausklappbar f�r jeden Stream (Um Platz zu sparen)
-Stream-Data mit Link zu geografischer Anzeige (Google-Maps) der IP-Adresse eines H�rers und Hostname
-Mod-Data ohne Anzeige der IP und des Hostnamens (Datenschutz) - sonst identisch zu Stream-Data
-Anzeige des verwendeten Programms mit Versionsnummer in Mod-Data und Stream-Data
-Anzeige, wie lange der H�rer schon eingeschaltet hat (Mod-Data und Stream-Data)
-und vieles mehr...

---------------------------------------
1. Installation
---------------------------------------

Inhalt des Ordners "files" muss in das "root"-Verzeichnis
der PHP-Fusion Installation kopiert werden.

Danach kann die Infusion �ber Administration -> System -> Infusionen
installiert werden.

Nach der Installation befindet sich ein Mittel-Panel auf der Webseite,
welches auf jeder Seite angezeigt wird.
Einstellungen hierzu k�nnen in Administration -> System -> Panels
angepasst werden.

Einstellungen zu den Streams k�nnen unter Administration -> Infusionen 
vorgenommen werden.

---------------------------------------
3. Changelog
---------------------------------------
v0.2.4-beta Verbesserung Adminpanel (Passw�rter als *****, Formularanzeige nur bei Editieren, mehr Einstellungen)
v0.2.3-beta Erweiterung auf mehrere Streams
v0.2.2-beta DJ-Anzeige wer gerade On Air ist mit Avatar / Einstellungen im Admin / Kleinere Verbesserungen
v0.2.1-beta Mod-Data (f�r Moderatoren) und Stream-Data (f�r Admins) integriert / Diese readme_de hinzugef�gt
v0.2.0-beta Konvertiert als PHP-Fusion Version 7.x Infusion

---------------------------------------
4. Update:
---------------------------------------
v0.2.3-beta => v0.2.4-beta: H�rer-Anzeige deinstallieren, alle Dateien aus dem Paket hochladen (�berschreiben) und H�rer-Anzeige neu installieren.
v0.2.2-beta => v0.2.3-beta: Alle Dateien aus dem Paket hochladen (�berschreiben)
v0.2.1-beta => v0.2.2-beta: H�rer-Anzeige deinstallieren, alle Dateien aus dem Paket hochladen (�berschreiben) und H�rer-Anzeige neu installieren.
v0.2.0-beta => v0.2.1-beta: H�rer-Anzeige deinstallieren, alle Dateien aus dem Paket hochladen (�berschreiben) und H�rer-Anzeige neu installieren.
Achtung: Vorhandene Einstellungen werden dabei gel�scht.

---------------------------------------
5. Thanks to:
---------------------------------------
-Myself
