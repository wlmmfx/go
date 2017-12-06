#!/bin/bash
#######################################################
# $Name:         clear_cache.sh
# $Version:      v1.0
# $Function:     Backup MySQL Databases Script
# $Author:       ShaoBo Wan (Tinywan)
# $organization: https://github.com/Tinywan
# $Create Date:  2017-11-29
# $Description:  清楚系统缓存文件
#######################################################
PATH=/usr/local/bin:/usr/bin:/bin
WEB_PATH=$1

if [ $# -ne 1 ] ; then
        echo 1;
        exit 1;
fi

if [ ! -d $WEB_PATH ]; then
	echo "2";
	exit 2;
fi

cd $WEB_PATH 
$(/usr/bin/php think clear >/dev/null 2>&1)
echo "0";
exit 0;




