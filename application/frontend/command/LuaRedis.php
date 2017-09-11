<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/9/9 14:51
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/

namespace app\frontend\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class LuaRedis extends Command
{
    protected function configure()
    {
        $this->setName('LuaRedis')->setDescription('Here is the LuaRedis ');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln("TestCommand: LuaRedis");
    }
}