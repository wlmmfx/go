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
API_SIGN=$1
API_URL="https://www.tinywan.com/api/open/videoEditConf"

API_CODE=$(curl -s "${API_URL}?sign=${API_SIGN}" | jq '.code' | sed 's/\"//g')
# [01] API Sign Check
if [ "${API_CODE}" == "500" ]; then
     echo -1      
     exit 1
fi

inputpath="/home/www/videos/"
datapath="/home/www/ffmpeg-data/"
liveId=$(curl -s "${API_URL}?sign=${API_SIGN}" | jq '.data.live_id' | sed 's/\"//g');
videofile=$(curl -s "${API_URL}?sign=${API_SIGN}" | jq '.data.origin_video_name' | sed 's/\"//g').mp4;

starttime=$(curl -s "${API_URL}?sign=${API_SIGN}" | jq '.data.start_time' | sed 's/\"//g');
endtime=$(curl -s "${API_URL}?sign=${API_SIGN}" | jq '.data.end_time' | sed 's/\"//g');
tempfile=$datapath`date +%s`.tmp.mp4;
outputfile=$(curl -s "${API_URL}?sign=${API_SIGN}" | jq '.data.new_video_id' | sed 's/\"//g');
outputvideo=$outputfile.mp4;
outputimage=$outputfile.jpg;
outputpng=$outputfile.png;
m3u8_dir=$outputfile;
cut_image_time=$(curl -s "${API_URL}?sign=${API_SIGN}" | jq '.data.cut_image_time' | sed 's/\"//g')
auto_slice=$(curl -s "${API_URL}?sign=${API_SIGN}" | jq '.data.auto_slice' | sed 's/\"//g')

# [02] 目录检查
if [ ! -d $datapath ]; then
    mkdir -p $datapath;
fi

# [03] oss 文件检查
if [ ! -f $datapath$videofile ];then
    osscmd get  oss://tinywan-oss/data/$liveId/video/$videofile  $datapath$videofile >>/dev/null 2>>/dev/null;
fi

# [04] oss 文件是否下载成功
if [ ! -f $datapath$videofile ];then
        echo "-2";
        exit 1;
fi

FFMPEG_CUT_FILE=$($FFMPEG_PATH -i $datapath$videofile -vcodec copy -acodec copy -ss $starttime -to $endtime  $tempfile -y >>/dev/null 2>>/dev/null  && echo "success" || echo "fail")
# [05] ffmpeg cut check
if [ ! -f $tempfile ]; then
#   LOG error " ffmpeg cut  file failed";
   echo -3
   exit 1;
fi

mv $tempfile $datapath$outputvideo;
# [06] 
if [ ! -f $datapath$outputvideo ]; then
    echo -4;
    exit 1;
fi

FFMPEG_CUT_IMAGE_JPG=$($FFMPEG_PATH -y -ss ${cut_image_time} -i $datapath$outputvideo  -vframes 1 $datapath$outputimage >>/dev/null 2>>/dev/null && echo "success" || echo "fail")

if [ ! -f $datapath$outputimage ]; then
        FFMPEG_CUT_IMAGE_PNG=$($FFMPEG_PATH -y -ss ${cut_image_time}  -analyzeduration 2147483647 -probesize 2147483647 -i $datapath$outputvideo  -vframes 1 $datapath$outputpng >>/dev/null 2>>/dev/null && echo "success" || echo "fail")
        convert $datapath$outputpng $datapath$outputimage >>/dev/null 2>>/dev/null;
fi

if [ ! -f $datapath$outputimage ]; then
    echo -5;
    exit 1;
fi

upload_oss(){
    osscmd put  $datapath$outputvideo oss://tinywan-oss/data/$liveId/video/$outputvideo  >>/dev/null 2>>/dev/null;
    osscmd put  $datapath$outputimage oss://tinywan-oss/data/$liveId/video/$outputimage  >>/dev/null 2>>/dev/null;
}

upload_oss

del_mp4_and_image(){
    cd $datapath
    find ./ -mindepth 1 -maxdepth 3 -type f -name "*.png" -mmin +2 | xargs rm -rf
    find ./ -mindepth 1 -maxdepth 3 -type f -name "*.mp4" -mmin +2 | xargs rm -rf
    find ./ -mindepth 1 -maxdepth 3 -type f -name "*.jpg" -mmin +2 | xargs rm -rf
}

del_mp4_and_image

echo 0;
exit 0;




