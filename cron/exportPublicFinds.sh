#!/bin/bash
DATE=$(date +"%F")
FILE="allFindsAvailable-$DATE.csv"
BACK="/home/beowulf2/backups/csv/publicAllFinds/"
ZIP=/usr/bin/zip
curl "http://81.29.72.73:8983/solr/beowulf/select?indent=on&version=2.2&q=workflow%3A%5B3+TO+4%5D&fq=&start=0&rows=1000000&fl=id%2Cobjecttype%2Cbroadperiod%2CperiodFromName%2CperiodToName%2Cfromdate%2Ctodate%2Cdescription%2Cnotes%2Cworkflow%2CmaterialTerm%2C+secondaryMaterialTerm%2CsubsequentActionTerm%2CdiscoveryMethod%2Cdatefound1%2Cdatefound2%2CTID%2CrallyName%2Cweight%2Cheight%2Cdiameter%2C+thickness%2Cdiameter%2Clength%2Cquantity%2Cidentifier%2Crecorder%2CdenominationName%2CrulerName%2CmintName%2CobverseDescription%2C+obverseLegend%2CreverseDescription%2CreverseLegend%2CtribeName%2CreeceID%2CcciNumber%2Cmintmark%2CabcType%2CcategoryTerm%2CtypeTerm%2C+moneyerName%2CreverseType%2CregionName%2Ccounty%2Cdistrict&qt=&wt=csv&explainOther=&hl.fl=" -o $BACK$FILE
cd $BACK
zip $FILE.zip $FILE
rm $BACK$FILE
ruby /home/danp/s3sync/s3sync.rb -r -s -v -p --delete $BACK findsorguk-publicfinds:  > /var/log/s3sync

