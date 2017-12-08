#!/bin/bash

# Simple Think_concole init.d script conceived to work on Linux systems
# as it does use of the /proc filesystem.
### BEGIN INIT INFO
# Provides:   think_concole
# Required-Start: $local_fs $network
# Required-Stop:  $local_fs
# Default-Start:  2 3 4 5
# Default-Stop:  0 1 6
# Short-Description: think_concole commang auto run 
# Description:  penavico think_concole
### END INIT INFO


PATH=/usr/local/bin:/usr/bin:/bin
PROJECT_PATH="/home/www/web/go-study-line"

THINK_SCRIPT='think' #服务脚本
THINK_NAME="send_mail"  #你自定义的服务名称
PID="${PROJECT_PATH}/shell/service/${THINK_NAME}.pid" #服务PID
LOG="${PROJECT_PATH}/shell/log/${THINK_NAME}.log" #服务运行日志 记录错误信息

#判断程序是否已经在运行
status_script(){
    ps -fe |grep ${THINK_SCRIPT}|grep ${THINK_NAME}|grep -v grep
    if [ $? -eq 0 ]
    then
        echo -e "\033[32m [$THINK_NAME] isRunning. found running with processes: $PID \033[0m"
        running=1

    elif [ $? -ne 0 ]
    then
	echo -e "\033[31m $THINK_NAME is NOT running. \033[0m"
        running=2
    fi
}

#启动脚本，先判断脚本是否已经在运行
start_script(){
status_script
    if [ ${running} -eq 1 ]
    then
        echo ${0}' Is starting ...'
    else
	echo -e "\033[34m [$THINK_NAME] Starting \033[0m"
        cd ${PROJECT_PATH}
        nohup php ${THINK_SCRIPT} ${THINK_NAME} >$LOG 2>&1 &
        echo $! > ${PID}
	echo -e "\033[34m [$THINK_NAME] Start finish PID: $! \033[0m"
    fi
}

#停止脚本
stop_script(){

status_script
    if [ ${running} -ne 1 ]
    then
        echo ${0}' no starting '$?...
    else
    PIDS=`ps aux|grep ${THINK_SCRIPT}|grep ${THINK_NAME}| grep -v grep |awk '{print $2}'`
       for kill_pid in ${PIDS}
       do
            kill -TERM ${kill_pid} >$LOG 2>&1
            echo "Kill pid ${kill_pid} .."
        done
        echo 'stop complete'
        return 1
    fi

}

#重启脚本
reload_script(){
    stop_script
    sleep 3
    start_script
}
#入口函数
handle(){
    case $1 in
    start)
        start_script
        ;;
    stop)
        stop_script
        ;;
    status)
        status_script
        ;;
    reload)
        reload_script
        ;;
    restart)
        reload_script
        ;;
    *)
	echo -e "\033[33m Usage: $0 {status|start|stop|restart|reload} \033[0m"
        ;;
    esac
}

if [ $# -eq 1 ]
then
    handle $1
else
    echo -e "\033[33m Usage: $0 {status|start|stop|restart|reload} \033[0m"	
fi
