#!/bin/sh
###########
##cplog.sh
###########

##define target log file.
file=/home/ubuntu/export_and_ftp/export_and_ftp.log

##define copy target path.
cpdir=/var/www/mlm/

##rm old log file.
rm /var/www/mlm/export_and_ftp.log

##cp log file.
cp $file $cpdir

exit 0
