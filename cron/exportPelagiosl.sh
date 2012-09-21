#!/bin/bash
FILE="pelagios.rdf"
BACK="/home/beowulf2/public_html/rdf/"
curl "81.29.72.73:8983/solr/beowulf/select?indent=on&version=2.2&q=objecttype%3Acoin+pleiadesID%3A%5B*+TO+*%5D&fq=&start=0&rows=10&fl=id%2Cobjecttype%2CmintNomisma%2CpleiadesID%2Cold_findID&qt=&wt=xslt&explainOther=&hl.fl=&tr=pelagios.xsl" -o $FILE
cd $BACK
zip $FILE.zip $FILE
#gzip $BACK$FILE.zip
ruby /home/danp/s3sync/s3sync.rb -r -s -v -p --delete $BACK findsorguk:  > /var/log/s3sync
#rm $BACK$FILE.gz

