<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/5 16:30
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\command;


use Swoole\Server;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class SwooleServer extends Command
{
    protected $swoole;

    public function configure()
    {
        $this->setName('socketServer')->setDescription('This is  Swoole socket service');
    }

    public function execute(Input $input, Output $output)
    {
        $this->swoole = new Server();
    }

}