## date format ##
NOW=$(date +"%F")
NOWT=$(date +"%T")
 
## Backup path ##
BAK="/home/beowulf2/backups/mysql"
 
## Login info ##
MUSER="webuser2"
MPASS="45jql11w"
MHOST="127.0.0.1"
 
## Binary path ##
MYSQL="/usr/bin/mysql"
MYSQLDUMP="/usr/bin/mysqldump"
GZIP="/bin/gzip"
 
## Get database list ##
DBS="$($MYSQL -u $MUSER -h $MHOST -p$MPASS -Bse 'show databases')"
 
## Use shell loop to backup each db ##
for db in $DBS
do
 FILE="$BAK/$db-$NOW.gz"
 $MYSQLDUMP -u $MUSER -h $MHOST -p$MPASS $db | $GZIP -9 > $FILE
done
