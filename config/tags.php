<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用行为扩展定义文件
return [
    // 应用初始化
    'app_init'     => [
        //'\app\common\behavior\Hello',
        //'\app\common\behavior\Test'
    ],
    // 应用开始
    'app_begin'    => [
        //'\app\common\behavior\Test'
    ],
    // 模块初始化
    'module_init'  => [
//        function($request){
//            echo 'hello,'.$request->module().'!<br/>';
//        },
    ],
    // 操作开始执行
    'action_begin' => [

    ],
    // 视图内容过滤
    'view_filter'  => [],
    // 日志写入
    'log_write'    => [],
    // 应用结束
    'app_end'      => [],
    // 任务失败统一回调,有四种定义方式
    'queue_failed'=> [

        // 数组形式，[ 'ClassName' , 'methodName']
        ['application\\behavior\\MyQueueFailedLogger', 'logAllFailedQueues']

        // 字符串(静态方法)，'StaicClassName::methodName'
        // 'MyQueueFailedLogger::logAllFailedQueues'

        // 字符串(对象方法)，'ClassName'，此时需在对应的ClassName类中添加一个名为 queueFailed 的方法
        // 'application\\behavior\\MyQueueFailedLogger'

        // 闭包形式
        /*
        function( &$jobObject , $extra){
            // var_dump($jobObject);
            return true;
        }
        */
    ],
    // 钩子绑定配置绑定
    'controller_init'	=>	[
        '\app\common\behavior\Check',
    ],
];
