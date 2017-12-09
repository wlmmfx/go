<?php
$servers=$argv[1];
$pwds=$argv[2];
if(empty($servers) && empty($pwds)){
    exit("参数错误");
}
file_put_contents('/home/www/web/go-study-line/shell/auto-install/shell.log',"servers----------------{$servers} \r\n");
file_put_contents('/home/www/web/go-study-line/shell/auto-install/shell.log',"pwds----------------${pwds} \r\n");
$shell_script = "/home/www/web/go-study-line/shell/auto-install/init-fix.sh";
//$shell_script = "/home/www/script/auto-install/init.sh";
$cmdStr = "{$shell_script} '{$servers}' '{$pwds}'";
file_put_contents('/home/www/web/go-study-line/shell/auto-install/shell.log',"----------cmdStr----------------$cmdStr \r\n");
//exit;
exec("{$cmdStr} >> /home/www/web/go-study-line/shell/auto-install/shell.log 2>&1 &", $results, $status );
//exec("{$cmdStr}", $results, $status );
if($status !== 0){
    file_put_contents('/home/www/web/go-study-line/shell/auto-install/shell.log',"init.sh 安装脚本启动失败，请检查配置后重现安装 \r\n");
    exit();
}else{
    file_put_contents('/home/www/web/go-study-line/shell/auto-install/shell.log',"init.sh 安装脚本启动成功  \r\n");
    file_put_contents('/home/www/web/go-study-line/shell/auto-install/shell.log',$results);
}
