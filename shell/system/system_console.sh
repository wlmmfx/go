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

if [ $# -ne 2 ] ; then
        echo "-1";
        exit -1;
fi

if [ ! -d $WEB_PATH ]; then
	echo "-2";
	exit -2;
fi

cd $WEB_PATH 

case "$2" in
  clear_cache)
	RES=$(php think clear >/dev/null 2>&1 && echo "200" || echo "500")
	if [ "${RES}" == "500" ]
	then
		echo "-3"
		exit -3
	fi
        ;;
  optimize_route)
	RES=$(php think optimize:route >/dev/null 2>&1 && echo "200" || echo "500")
        if [ "${RES}" == "500" ]
        then
                echo "-4"
                exit -4
        fi
        ;;
  optimize_config)
        RES=$(php think optimize:config >/dev/null 2>&1 && echo "200" || echo "500")
        if [ "${RES}" == "500" ]
        then
                echo "-5"
                exit -5
        fi
        ;;
  optimize_schema)
        RES=$(php think optimize:schema >/dev/null 2>&1 && echo "200" || echo "500")
        if [ "${RES}" == "500" ]
        then
                echo "-6"
                exit -6
        fi
        ;;
  *)
        echo '-10'
        exit -10
        ;;
esac

echo "0";
exit 0;




