<?php
/**
 * Created by PhpStorm.
 * User: Tinywan
 * Date: 2017/8/7
 * Time: 17:41
 * Mail: Overcome.wan@Gmail.com think queue:work --queue YourQueue --sleep 3
 */
echo "argv:".print_r($argv)."\r\n";
$command = 'queue:work';
exec("php think queue:work --queue ".$argv[1]);
