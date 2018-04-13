<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/4/13 9:30
 * |  Mail: Overcome.wan@Gmail.com
 * |  Desc: 描述信息
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\test\controller;


use think\Controller;

class StreamController extends Controller
{
    // 包装器
    public function getWrapper()
    {
        /**
         * 获取已注册的套接字传输协议列表
         */
        var_dump(stream_get_transports());
        //array (size=9)
        //  0 => string 'tcp' (length=3)
        //  1 => string 'udp' (length=3)
        //  2 => string 'unix' (length=4)
        //  3 => string 'udg' (length=3)
        //  4 => string 'ssl' (length=3)
        //  5 => string 'tls' (length=3)
        //  6 => string 'tlsv1.0' (length=7)
        //  7 => string 'tlsv1.1' (length=7)
        //  8 => string 'tlsv1.2' (length=7)

        /**
         * 获取已注册的流类型
         */
        var_dump(stream_get_wrappers());
        // array (size=17)
        //   0 => string 'https' (length=5)
        //   1 => string 'ftps' (length=4)
        //   2 => string 'compress.zlib' (length=13)
        //   3 => string 'compress.bzip2' ength=14)
        //   4 => string 'php' (length=3)
        //   5 => string 'file' (length=4)
        //   6 => string 'glob' (length=4)
        //   7 => string 'data' (length=4)
        //   8 => string 'http' (length=4)
        //   9 => string 'ftp' (length=3)
        //   10 => string 'phar' (length=4)
        //   11 => string 'zip' (length=3)
        //   12 => string 'ssh2.shell' (length=10)
        //   13 => string 'ssh2.exec' (length=9)
        //   14 => string 'ssh2.tunnel' (length=11)
        //   15 => string 'ssh2.scp' (length=8)
        //   16 => string 'ssh2.sftp' (length=9)

        /**
         * 获取已注册的数据流过滤器列表
         */
        var_dump(stream_get_filters());
        //array (size=12)
        //  0 => string 'zlib.*' (length=6)
        //  1 => string 'bzip2.*' (length=7)
        //  2 => string 'convert.iconv.*' (length=15)
        //  3 => string 'mcrypt.*' (length=8)
        //  4 => string 'mdecrypt.*' (length=10)
        //  5 => string 'string.rot13' (length=12)
        //  6 => string 'string.toupper' (length=14)
        //  7 => string 'string.tolower' (length=14)
        //  8 => string 'string.strip_tags' (length=17)
        //  9 => string 'convert.*' (length=9)
        //  10 => string 'consumed' (length=8)
        //  11 => string 'dechunk' (length=7)
    }

    public function phpInput()
    {
        $param = file_get_contents('php://input');
        return $param;
    }

    public function readfile()
    {
        $content = readfile('/home/www/bin/count.sh');
        var_dump($content); // #!/bin/bash for ((COUNT = 1; COUNT <= 10; COUNT++)); do echo $COUNT sleep 1 done
        $content2 = readfile('file:///home/www/bin/count.sh');
        var_dump($content2); // #!/bin/bash for ((COUNT = 1; COUNT <= 10; COUNT++)); do echo $COUNT sleep 1 done

        // 逐行读取文件
        $file = fopen("file:///home/www/bin/count.sh", "r") or exit("无法打开文件!");
        // 读取文件每一行，直到文件结尾
        while (!feof($file)) {
            echo fgets($file) . "<br>";
        }
        fclose($file);

        // #!/bin/bash
        // for ((COUNT = 1; COUNT <= 10; COUNT++)); do
        // echo $COUNT
        // sleep 1
        // done

        // 逐字符读取文件
        $file2 = fopen("file:///home/www/bin/count.sh", "r") or exit("无法打开文件!");
        while (!feof($file2)) {
            echo fgetc($file2); // #!/bin/bash for ((COUNT = 1; COUNT <= 10; COUNT++)); do echo $COUNT sleep 1 done
        }
        fclose($file2);
    }

    public function getHttpStatusMessage($statusCode)
    {
        $httpStatus = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'];
        return $httpStatus[$statusCode] ? $httpStatus[$statusCode] : $httpStatus[500];
    }
}