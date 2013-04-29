#!/bin/bash
DATE=$(date +"%F")
FILE="allfinds-$DATE.csv"
BACK="/home/beowulf2/backups/csv/"
FIELDS="id,old_findID,objecttype,broadperiod,subperiodFrom,subperiodTo,periodFromName,periodToName,fromdate,todate,description,notes,workflow,materialTerm,secondaryMaterialTerm,subsequentActionTerm,discoveryMethod,datefound1,datefound2,TID,rallyName,weight,height,diameter,thickness,diameter,length,quantity,finder,identifier,recorder,denominationName,rulerName,mintName,obverseDescription,obverseLegend,reverseDescription,reverseLegend,tribeName,reeceID,cciNumber,mintmark,abcType,categoryTerm,typeTerm,moneyerName,reverseType,regionName,county,district,parish,knownas,gridref,gridSource,fourFigure,easting,northing,latitude,longitude,fourFigureLat,fourFigureLon,geohash,coordinates,elevation"ZIP=/usr/bin/zip
curl "http://81.29.72.73:8983/solr/beowulf/select?indent=on&version=2.2&q=*%3A*&fq=&start=0&rows=1&fl=$FIELDS&qt=&wt=csv&explainOther=&hl.fl=" -o $BACK$FILE
cd $BACK
zip $FILE.zip $FILE
rm $BACK$FILE
#gzip $BACK$FILE.zip
find $BACK* -type f -mtime +10 -exec rm '{}' \;
ruby /home/danp/s3sync/s3sync.rb -r -s -v -p --exclude="publicAllFinds$|publicGrids$" --delete $BACK findsorguk:  > /var/log/s3sync
