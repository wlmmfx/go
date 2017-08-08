<?php
/**
 * Created by PhpStorm.
 * User: Tinywan
 * Date: 2017/8/7
 * Time: 15:43
 * Mail: Overcome.wan@Gmail.com
 */

namespace app\queue\consumer;


class MyJob
{
    public function perform()
    {
        // Work work work
        echo $this->args['name'];
    }
}