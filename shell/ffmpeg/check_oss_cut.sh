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

## {$shell_script} {$activityid2} {$sourcefile} {$starttime} {$endtime} {$editid} {$video_desc} {$auto_slice}
LOG info "all param $0 $1 $2 $3 $4 $5 $6 $7";

inputpath="/home/www/videos/"
datapath="/home/www/ffmpeg-data/"
liveId=$1;
videofile=$2.mp4;

starttime=$3;
endtime=$4;
tempfile=$datapath`date +%s`.tmp.mp4;
outputfile=$5;
outputvideo=$5.mp4;
outputimage=$5.jpg;
outputpng=$5.png;
actualLiveId=$6;
m3u8_dir=$5;
auto_slice=$7

if [ ! -d $datapath ]; then
    mkdir -p $datapath;
fi

# oss get
if [ ! -f $datapath$videofile ];then
    LOG debug "liveId = $liveId videofile = $videofile 33 = $datapath$videofile"
    osscmd get  oss://tinywan-oss/data/$liveId/video/$videofile  $datapath$videofile >>/dev/null 2>>/dev/null;
fi

if [ ! -f $datapath$videofile ];then
    LOG error " oss download file failed ";
        echo "-8";
        exit -8;
fi

FFMPEG_CUT_FILE=$($FFMPEG_PATH -i $datapath$videofile -vcodec copy -acodec copy -ss $starttime -to $endtime  $tempfile -y >>/dev/null 2>>/dev/null  && echo "success" || echo "fail")
LOG info "ffmpeg cut file : ${FFMPEG_CUT_FILE} "

if [ ! -f $tempfile ]; then
   LOG error " ffmpeg cut  file failed";
   echo -7
   exit -7;
fi

mv $tempfile $datapath$outputvideo;

if [ ! -f $datapath$outputvideo ]; then
    LOG error "rename file error, disk is full";
    echo -6;
    exit -6;
fi

FFMPEG_CUT_IMAGE_JPG=$($FFMPEG_PATH -y -ss 00:00:10 -i $datapath$outputvideo  -vframes 1 $datapath$outputimage >>/dev/null 2>>/dev/null && echo "success" || echo "fail")
LOG info "ffmpeg cut jpeg image : ${FFMPEG_CUT_IMAGE_JPG} "

if [ ! -f $datapath$outputimage ]; then
        FFMPEG_CUT_IMAGE_PNG=$($FFMPEG_PATH -y -ss 00:00:10  -analyzeduration 2147483647 -probesize 2147483647 -i $datapath$outputvideo  -vframes 1 $datapath$outputpng >>/dev/null 2>>/dev/null && echo "success" || echo "fail")
        LOG debug "ffmpeg cut png image : ${FFMPEG_CUT_IMAGE_PNG} "
        convert $datapath$outputpng $datapath$outputimage >>/dev/null 2>>/dev/null;
fi

if [ ! -f $datapath$outputimage ]; then
    LOG error "create thumbnail error";
    echo -5;
    exit -5;
fi

LOG debug "datapath: $datapath$outputvideo liveid: ${liveId} outputvideo: ${outputvideo}"
upload_oss(){
    osscmd put  $datapath$outputvideo oss://tinywan-oss/data/$liveId/video/$outputvideo  >>/dev/null 2>>/dev/null;
    osscmd put  $datapath$outputimage oss://tinywan-oss/data/$liveId/video/$outputimage  >>/dev/null 2>>/dev/null;
    LOG debug "osscmd upload : $? "
}
upload_oss

del_mp4_and_image(){
    cd $datapath
    find ./ -mindepth 1 -maxdepth 3 -type f -name "*.png" -mmin +2 | xargs rm -rf
    find ./ -mindepth 1 -maxdepth 3 -type f -name "*.mp4" -mmin +2 | xargs rm -rf
    find ./ -mindepth 1 -maxdepth 3 -type f -name "*.jpg" -mmin +2 | xargs rm -rf
}

del_mp4_and_image

echo "0";
exit 0;




