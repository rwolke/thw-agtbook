# Atemschutznachweis für Tätigkeit unter Atemschutz

[Download Musterpass](https://raw.githubusercontent.com/rwolke/thw-agtbook/master/a5.pdf)

## Konzept

### Rechtlicher Rahmen

Ein Atemschutznachweis muss gemäß DV 7 für jeden Atemschutzgeräteträger geführt werden. In ihm sind

* die Untersuchungstermine nach G 26, 
* absolvierte Aus- und Fortbildung und 
* die Unterweisungen sowie 
* die Einsätze unter Atemschutz 

dokumentiert. Der Dienststellenleiter oder eine Beauftragte Person muss diese Angaben bestätigen.

Für jeden Einsatz muss zusätzlich noch 

* Datum und Einsatzort
* Art des Gerätes
* Atemschutzeinsatzzeit (Minuten)
* Tätigkeit 

dokumentiert werden. 

Im THW sind über die DV 500 noch zusätzliche Ausbildungen, Unterweisungen und Übungen verfügt, um Kameraden für Einsätze in CBRN-Lagen zu qualifizieren und einsatzbereit zu halten. Dafür stehen jedoch keine Vorlagen zur Verfügung.

### Umsetzung

Kern des AGT-Passes ist eine Aktivitäten-Übersicht. In dieser werden für einen Zeitraum alle AGT-relevanten Unterweisungen, Übungen und Einsätze dokumentiert. So ist auf einen Blick zu erkennen, ob ein AGT einsatzbereit ist oder ihm z.B. eine Unterweisung oder Einsatzübung fehlt.

![Aktivitäten unter AGT - Muster](https://raw.githubusercontent.com/rwolke/thw-agtbook/master/muster.png)

Ebenso können zusätzliche relevante Informationen zu dem Einsatz erfasst werden: Bestand eine CBRN-Lage? Wurde ein CSA getragen?

Im Kalender wird der Zeitpunkt an dem eine Unterweisung oder Belastungsübung erfolgte mit einem X gekennzeichnet und die nächsten 11 Monate gestrichen. Bei Einsätzen wird stattdessen die fortlaufende EinsatzNr verwendet. Ist im Kalender der aktuelle Monat mit waagerechten Strichen oder gefüllt, ist der AGT einsatzbereit. Zusätzlich sollte mit Marker die Gültigkeit der G26.3 farblich eingetragen werden.

## Selbst erstellen & gestalten

Da einige Abhängigkeiten nicht öffentlich bereitgestellt werden können, ist etwas Handarbeit notwendig um den Pass selbst zu generieren:

### Systemvoraussetzungen

Neben PHP mit aktiver gd-Erweiterung sollte auf dem System auch imagemagick installiert sein um RGB-Bilder in den CMYK-Farbraum zu konvertieren. Sollte imagemagick nicht installiert sein, wird eine Warnung ausgegeben und das Bild im RGB-Format eingebettet. 

### Schriftarten herunterladen

#### Bundesschriftarten

Der Pass verwendet die offiziellen Schriftarten der Bundesregierung. Diese können als THW-Angehöriger z.B. aus dem Extranet bezogen werden. Verwandt wird die BundesSans-Schriftart in den Office-Varianten.

Die Dateien **BundesSansOffice-Bold.ttf**, **BundesSansOffice-Italic.ttf** und **BundesSansOffice-Regular.ttf** müssen in das Verzeichnis pdf/font/ gelegt werden.

#### THW-Schriftart

Um das Logo mit dem Ortsverband zu versehen muss die Schrifart **Lubalin Graph Bold.ttf** in das Verzeichnis pdf/font/ gelegt werden.

#### Handschriftarten

Für die Mustereintragungen im Pass werden zwei verschiedene Handschriftarten verwendet. Diese sind ArchitectsDaughter und GloriaHallelujah. 
Beide wurden von Kimberly Geswein entwickelt und stehen unter der SIL Open Font Licence u.a. bei Google Fonts abrufbar. 

### Anpassungen

Die wichtigsten vorgesehenen Konfigrationsoptionen um das Layout anzupassen sind in der make.php zu Beginn aufgeführt und erklärt. Hier kann das Titelbild angepasst werden oder eine andere Zusammenstellung an Inhalten vorgenommen werden.

