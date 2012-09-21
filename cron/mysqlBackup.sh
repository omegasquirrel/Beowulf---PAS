#!/bin/bash
BACK="/home/beowulf2/backups/mysql/"
ruby /home/danp/s3sync/s3sync.rb -r -s -v -p --delete $BACK dbback:  > /var/log/s3sync
find /home/beowulf2/backups/mysql/ -name "*.gz" -mtime +30 -exec rm -f {} \;
