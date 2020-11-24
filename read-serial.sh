#!/bin/sh

###  Pfad und Dateinamen initialisieren
datapath=/home/pi/smart-home/data/
fileext=_$(date +%Y%m%d%H%M%S).txt

### Binaer auf lesbar umstellen
stty -F /dev/ttyUSB0 1:0:8bd:0:3:1c:7f:15:4:5:1:0:11:13:1a:0:12:f:17:16:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0 &
 
### Daten von serieller Schhnittstelle einlesen und in Datei schreiben (10 Sekunden lang)
cat /dev/ttyUSB0 | od -tx1 > ${datapath}serialin${fileext}  &
sleep 10
kill -TERM $!

### Script zum Auswerten des SML-Datenstroms aufrufen
php /home/pi/smart-home/sml.php type=DWS7420 smartMeterId=id-smart.meter url=https://localhost/api/v1/smartmeter/data token=1234567890 path=/smart-home/data/

### Jede Minute per crontab starten
# */1 * * * *  pi /home/pi/smart-home/read-serial.sh > /home/pi/smart-home/output.txt
