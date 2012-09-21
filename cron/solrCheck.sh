#!/bin/bash
response=$(curl -s -I -L 'http://81.29.72.73:8983/solr/beowulf/admin/ping?echoParams=none&omitHeader=on' | grep HTTP); 

status=${response#* }; # Strip off characters up to the first space
status=${status:0:3}; # Just use the 3 digit status code

if [ "$status" != "200" ] 
#echo "Up for use";
then
    killall java 
    /etc/init.d/solr restart
    echo "Solr server down, restarted";
fi
