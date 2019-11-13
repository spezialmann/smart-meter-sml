#!/bin/sh

###  Pfad und Dateinamen initialisieren
datapath=/home/pi/smart-home/data/
fileext=_$(date +%Y%m%d%H%M%S).txt

### Binaer auf lesbar umstellen
stty -F /dev/ttyUSB0 1:0:8bd:0:3:1c:7f:15:4:5:1:0:11:13:1a:0:12:f:17:16:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0 &
 
### Daten von serieller Schhnittstelle einlesen
cat /dev/ttyUSB0 | od -tx1 > ${datapath}serialin${fileext}  &
sleep 10
kill -TERM $!

### Script zum Auswerten des SML-Datenstroms aufrufen
php /var/www/html/sml.php
