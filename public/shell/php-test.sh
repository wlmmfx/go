#!/bin/bash
#######################################################
# $Name:         check_oss_cut.sh
# $Version:      v1.0
# $Function:     Backup MySQL Databases Script
# $Author:       ShaoBo Wan (Tinywan)
# $organization: https://github.com/Tinywan
# $Create Date:  2017-06-29
# $Description:  OSS视频剪切
#######################################################
PATH=/usr/local/bin:/usr/bin:/bin
YM=`date +%Y%m`
FLOG="/home/www/ffmpeg-data/oss_video_cut_${YM}.log"
loglevel=0 #debug:0; info:1; warn:2; error:3
TIME=`date '+%Y-%m-%d %H:%M:%S'`
FFMPEG_PATH="/usr/bin/ffmpeg"
function LOG(){
        local log_type=$1
        local LOG_CONTENT=$2
        logformat="`date '+%Y-%m-%d %H:%M:%S'` \t[${log_type}]\tFunction: ${FUNCNAME[@]}\t[line:`caller 0 | awk '{print$1}'`]\t [log_info: ${LOG_CONTENT}]"
        {
        case $log_type in
                debug)
                        [[ $loglevel -le 0 ]] && echo -e "\033[34m${logformat}\033[0m" ;;
                info)
                        [[ $loglevel -le 1 ]] && echo -e "\033[32m${logformat}\033[0m" ;;
                warn)
                        [[ $loglevel -le 2 ]] && echo -e "\033[33m${logformat}\033[0m" ;;
                error)
                        [[ $loglevel -le 3 ]] && echo -e "\033[31m${logformat}\033[0m" ;;
        esac
        } | tee -a $FLOG
}


if [ $# -ne 7 ] ; then
        LOG error "current param is $# ,need 7 param";
        echo -9;
        exit -9;
fi

LOG info "all param $0 $1 $2 $3 $4 $5 $6 $7";
 exit 0;






