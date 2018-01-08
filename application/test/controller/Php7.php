<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/19 8:55
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\test\controller;


class Php7
{

    /**
     * 简单的PHP函数
     */
    public function createRange($number)
    {
        $data = [];
        for ($i = 0; $i < $number; $i++) {
            $data[] = time();
        }
        return $data;
    }

    /**
     *  打印结果：
     *  1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     */
    public function simpleFunction()
    {
        $result = $this->createRange(10); // 这里调用上面我们创建的函数
        foreach ($result as $value) {
            sleep(1); //这里停顿1秒，我们后续有用
            echo $value . '<br />';
        }
    }

    /**
     * 创建生成器
     * @param $number
     * @return \Generator
     */
    function yieldCreateRange($number)
    {
        for ($i = 0; $i < $number; $i++) {
            yield time();
        }
    }

    /**
     * 使用简单的生成器
     *  打印结果：
     *  1513174966
     * 1513174967
     * 1513174968
     * 1513174969
     * 1513174970
     * 1513174971
     * 1513174972
     * 1513174973
     * 1513174974
     * 1513174975
     */
    public function yieldSimpleFunction()
    {
        $result = $this->yieldCreateRange(10); // 这里调用上面我们创建的函数
        foreach ($result as $value) {
            sleep(1); //这里停顿1秒，我们后续有用
            echo $value . '<br />';
        }
    }

    /**
     * 实际开发应用
     */
    public function readTextFunction()
    {
        header("content-type:text/html;charset=utf-8");
        $handle = fopen(ROOT_PATH . 'public' . DS . 'uploads/test.txt', 'rb');
        while (feof($handle) === false) {
            # code...
            yield fgets($handle);
        }
        fclose($handle);
    }

    /**
     * 输出文本内容
     */
    public function echoTextFunction()
    {
        foreach ($this->readTextFunction() as $key => $value) {
            echo $value . '<br />';
        }
        echo 11111111111;
    }
}

