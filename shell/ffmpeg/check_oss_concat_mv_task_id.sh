#!/bin/bash
#######################################################
# $Name:         check_oss_concat_mv.sh.sh
# $Version:      v1.0
# $Function:     Backup MySQL Databases Script
# $Author:       ShaoBo Wan (Tinywan)
# $organization: https://github.com/Tinywan
# $Create Date:  2017-06-29
# $Description:  OSS视频合并
#######################################################
PATH=/usr/local/bin:/usr/bin:/bin
YM=`date +%Y%m`
FLOG="/home/www/ffmpeg-data/oss_video_concat_${YM}.log"
loglevel=0 #debug:0; info:1; warn:2; error:3
TIME=`date '+%Y-%m-%d %H:%M:%S'`
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
NEW_VIDEO_ID=$(curl -s "${API_URL}?sign=${API_SIGN}" | jq '.data.new_video_id' | sed 's/\"//g');
ALL_PARM_ARR=$(curl -s "${API_URL}?sign=${API_SIGN}" | jq '.data.all_param' | sed 's/\"//g');
#LOG debug "liveId = ${liveId} NEW_VIDEO_ID= ${NEW_VIDEO_ID} ALL_PARM=${ALL_PARM} SERVER_VAR=${SERVER_VAR}"

OLD_IFS="$IFS"
IFS=","
ALL_PARM=($ALL_PARM_ARR)
IFS="$OLD_IFS"

# 临时文件MP4
tempfile=`date +%s`.tmp.mp4;
# 新视频ID
outputvideo=$NEW_VIDEO_ID.mp4;
outputimage=$NEW_VIDEO_ID.jpg
#liveId=$2;
# mkdir dir
if [ ! -d $datapath ]; then
   mkdir -p $datapath;
fi

cd $datapath;
# 【1】链接所有的 MP4 文件为一个字符串或者数组
ALL_NUM=${#ALL_PARM[@]}
#echo 'SERVERS==========='${#ALL_PARM[@]}
# 1509350694806 CY00023 CY000235388-149907238720170703165947 CY00026 CY000265388-149913751020170704110510 1
FILES='';
# 循环遍历下载需要处理的文件
for ((k=0; k<=$(expr $ALL_NUM - 1); k=k+2));do
        # 偶数的$k 赋值为当前的LIVEID ,为了OSS下载文件的路径需要
	eval LIVEID=${ALL_PARM[$k]};
        # 奇数获取响应的活动LIVEID目录下的具体MP4文件名称
        eval ORIG_NAME=${ALL_PARM[$k + 1]};
        #LOG debug "LIVEID = "$LIVEID        #   L00089
        #LOG debug "ORIG_NAME = "$ORIG_NAME  #   CY000098778-149518660820170519173648
        ORIG_PATH_FILE=${ORIG_NAME}.mp4;

        #LOG warn "check all mp4 file  "$ORIG_PATH_FILE;     #   CY000098778-149518660820170519173648.mp4
        #检查文件是否存在
        if [ ! -f $ORIG_PATH_FILE ];then
                osscmd get  oss://tinywan-oss/data/$LIVEID/video/$ORIG_NAME.mp4  $ORIG_PATH_FILE >/dev/null 2>/dev/null;
                #LOG debug "file not exist, oss downloading... "
        fi

        if [ ! -f $ORIG_PATH_FILE ];then
            #LOG error "oss file download failed "
            echo "-2";
            exit 1;
        fi
        # 字符串拼接所有的MP4文件 eg:CY000235388-149907238720170703165947.mp4 CY000265388-149913751020170704110510.mp4 
        FILES=$FILES' '$ORIG_PATH_FILE;
done
#LOG info '-------------------------------------------'$FILES
prefix=$datapth`date +%F-%H-%M-%S`;
concat='concat:';
let "index = 0";
let "tsfile_count = 0 ";

# 【2】遍历所有文件,首先需要把mp4格式的文件转成ts格式
for i in $FILES
do
    #LOG debug "index: "$i;      #   CY000098778-149518660820170519173648.mp4
    if [ ! -f $i ] ;then
        #LOG error "file not exist";
        exit 2001;
    else
        tempname=${prefix}_${index}".ts";
        #LOG debug "tempname == "$tempname   #   2017-06-23-16-29-36_0.ts
        $FFMPEG_PATH  -i $i -acodec copy -vcodec copy -bsf:v h264_mp4toannexb  $tempname  >/dev/null 2>&1;
        if [ $index -eq 0 ]; then
            concat=$concat${tempname};
        else
            concat=$concat"|"$tempname;
        fi
        #echo $concat;
        #LOG debug "last concat: "$concat;   #   concat:2017-06-23-16-29-36_0.ts|2017-06-23-16-29-36_1.ts|2017-06-23-16-29-36_2.ts
    fi
    let "index =  index +1"  ;
    let "tsfile_count = tsfile_count + 1";
done

#LOG debug "concat : "$concat;   #   concat:2017-06-23-16-29-36_0.ts|2017-06-23-16-29-36_1.ts|2017-06-23-16-29-36_2.ts

# 【4】拼接好之后，再将ts封装格式转换回 mp4
$FFMPEG_PATH -i "$concat" -acodec copy -vcodec copy -absf aac_adtstoasc $tempfile >/dev/null 2>&1;

# 【5】删除ts 文件
let "index = 0";
while [ $index -lt $tsfile_count ]
do
    rm ${prefix}_${index}.ts;
    let "index = index +1";
done

# 【6】赋予文件权限
if [ ! -f $tempfile ]; then
   #LOG error "ffmpeg concat file failed";
   exit -3
   exit 1;
fi

# 【7】重名临时MP4文件为新名称的MP4视频文件
mv $tempfile $datapath$outputvideo;

if [ ! -f $datapath$outputvideo ]; then
    #LOG error "move file error, disk is full? ";
    echo -4;
    exit 1;
fi

# 【8】截取图片
$FFMPEG_PATH -analyzeduration 2147483647 -probesize 2147483647  -y -ss 00:00:10 -i $datapath$outputvideo  -vframes 1 $datapath/$outputimage  >>/dev/null 2>>/dev/null;
if [ ! -f $datapath$outputimage ]; then
    #LOG error "create thumbnail error";
    echo -5;
    exit 1;
fi

# oss upload
upload_oss(){
    osscmd put  $datapath$outputimage oss://tinywan-oss/data/$liveId/video/$outputimage >>/dev/null 2>>/dev/null;
    osscmd put  $datapath$outputvideo oss://tinywan-oss/data/$liveId/video/$outputvideo >>/dev/null 2>>/dev/null ;
}
upload_oss

del_mp4_and_image(){
    cd $datapath
    find ./ -mindepth 1 -maxdepth 3 -type f -name "*.png" -mmin +2 | xargs rm -rf
    find ./ -mindepth 1 -maxdepth 3 -type f -name "*.mp4" -mmin +2 | xargs rm -rf
    find ./ -mindepth 1 -maxdepth 3 -type f -name "*.jpg" -mmin +2 | xargs rm -rf
}

del_mp4_and_image

#LOG info "concat finished"
echo 0;
exit 0;


