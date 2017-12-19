<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/19 9:00
 * |  Mail: Overcome.wan@Gmail.com
 * |  Help: https://segmentfault.com/a/1190000012457145
 * |  Function: PHP7下的协程实现
 * '------------------------------------------------------------------------------------------------------------------*/

namespace system;

class MyIterator implements \Iterator
{
    private $var = [];

    public function __construct($array)
    {
        if (is_array($array)) {
            $this->var = $array;
        }
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
        echo "rewindind \n";
        reset($this->var);
    }

    public function current()
    {
        $var = current($this->var);
        echo "current: $var\n";
        return $var;
    }

    public function key()
    {
        $var = key($this->var);
        echo "key: $var\n";
        return $var;
    }

    public function next()
    {
        $var = next($this->var);
        echo "next: $var\n";
        return $var;
    }

    public function valid()
    {
        $var = $this->current() !== false;
        echo "valid: {$var}\n";
        return $var;
    }
}