<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 10:47
 */
return [
    "bnews/:id"=>"backend/index/info", #   http://127.0.0.1/news/234
    "d/:id"=>"frontend/index/detail", #    文章详细页面：http://test.thinkphp5-line.com/d/23.html
    "t/:id"=>"frontend/index/searchByTagId", #    标签页面：http://test.thinkphp5-line.com/t/3.html
//    "news/:id"=>"frontend/index/info", #   http://127.0.0.1/news/234
];