<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/11/15 17:18
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\frontend\controller;


use app\common\controller\BaseFrontend;
use Ehann\RediSearch\Fields\NumericField;
use Ehann\RediSearch\Fields\TextField;
use Ehann\RediSearch\Index;
use Ehann\Tests\RediSearch\Fields\NumericFieldTest;

class Redis extends BaseFrontend
{
    public function demo1()
    {
        return 'redis4.0';
    }

    /**
     * 创建架构
     */
    public function demo2()
    {
        $bookIndex = new Index();
        // bool(true)
        $inste = $bookIndex->addTextField('title')
            ->addTextField('author')
            ->addNumericField('price')
            ->addNumericField('stock')
            ->create();
        halt($inste);
    }

    /**
     * Add a Document
     */
    public function addDocument(){
        $bookIndex = new Index();
        $instance = $bookIndex->add([
            new TextField('title', 'Tale of Two Cities'),
            new TextField('author', 'Charles Dickens'),
            new NumericField('price', 9.99),
            new NumericField('stock', 231),
        ]);
        halt($instance);
    }

    /**
     * Search the Index
     */
    public function searchTheIndex(){
        $bookIndex = new Index();
        $result = $bookIndex->search('two cities');
        halt($result->count());     // Number of documents.

        // Documents are returned as objects by default.
//        $firstResult = $result->documents()[0];
//        $firstResult->title;
//        $firstResult->author;
    }
}