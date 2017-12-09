<?php
$sign = $argv[1];
if(empty($sign)){
    exit("sign is null 参数错误");
}
$shell_script = "/home/www/web/go-study-line/shell/auto-install/init-fix-josn.sh";
$cmdStr = "{$shell_script} '{$sign}'";
file_put_contents('/home/www/web/go-study-line/shell/auto-install/shell.log',"----------PHP Cli EXEC SCRIPT : $cmdStr \r\n");
#exit(1);
exec("{$cmdStr} >> /home/www/web/go-study-line/shell/auto-install/shell.log 2>&1 &", $results, $status );
//exec("{$cmdStr}", $results, $status );
if($status !== 0){
    file_put_contents('/home/www/web/go-study-line/shell/auto-install/shell.log',"init.sh 安装脚本启动失败，请检查配置后重现安装 \r\n");
    exit();
}else{
    file_put_contents('/home/www/web/go-study-line/shell/auto-install/shell.log',"init.sh 安装脚本启动成功  \r\n");
    file_put_contents('/home/www/web/go-study-line/shell/auto-install/shell.log',$results);
}
