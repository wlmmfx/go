<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/27
 * Time: 22:01
 */

namespace app\frontend\controller;

use think\Controller;
use think\Db;

class Excel extends Controller
{
    public function index()
    {
        //创建对象
        $excel = new \PHPExcel();
        //Excel表格式,这里简略写了8列
        $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'F', 'G');
        //表头数组
        $tableheader = array('ID', '标题', '内容');
        //填充表头信息
        for ($i = 0; $i < count($tableheader); $i++) {
            $excel->getActiveSheet()->setCellValue("$letter[$i]1", "$tableheader[$i]");
        }
        $data = array(
            array('1', '小王', '男', '20', '100'),
            array('2', '小李', '男', '20', '101'),
            array('3', '小张', '女', '20', '102'),
            array('4', '小赵', '女', '20', '103')
        );

        for ($i = 2; $i <= count($data) + 1; $i++) {
            $j = 0;
            foreach ($data[$i - 2] as $key => $value) {
                $excel->getActiveSheet()->setCellValue("$letter[$j]$i", "$value");
                $j++;
            }
        }
        //创建Excel输入对象
        $write = new \PHPExcel_Writer_Excel5($excel);
//        $write->save(ROOT_PATH . 'public' . DS . 'Excel'.date('Y-m-d',time()).mt_rand(0,999).'.xls'); resty_invitation_live_info
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="testdata.xls"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }

    /**
     * 根据活动好导出导出邀请码数据
     */
    public function invitation()
    {
        $liveId = "L02359";
        //创建对象
        $objPHPExcel = new \PHPExcel();
        //设置文件的一些属性，在xls文件——>属性——>详细信息里可以看到这些值，xml表格里是没有这些值的
        $objPHPExcel
            ->getProperties()//获得文件属性对象，给下文提供设置资源
            ->setCreator("Tinywan")
            ->setTitle($liveId . "活动邀请码 Excel 表格")
            ->setSubject("Office 2007 XLSX Test Document");

        //这里是根据Get过来的数组判断要导出的Excel的列数目
        $letterPost = ['11A', 'B11', 'C111', 'D1', 'D1', 'D1', 'D123423432'];
        static $letter = [];
        //获取Excel 头部的大写字母
        for ($i = 65; $i < (64 + count($letterPost)); $i++) {
            $letter[] = strtoupper(chr($i));
        }
        //表头数组
        $tableHeader = ['活动ID', '用户昵称', '邀请码', '邀请码创建时间', '邀请码使用时间', '到期时间'];
        $objSheet = $objPHPExcel->getActiveSheet();   //获取当前活动sheet
        $objSheet->setTitle($liveId . " 活动邀请码");   //给当前活动sheet起个名称
        //给表格添加数据
        for ($i = 0; $i < count($tableHeader); $i++) {
            //查询数据库
            $data = Db::table("resty_invitation_live_info")
                ->alias('l')
                ->join("resty_invitation i", "i.infoId = l.id")
                ->where("l.liveId", $liveId)
                ->select();
            //填充表头信息
            $objPHPExcel->getActiveSheet()->setCellValue("$letter[$i]1", "$tableHeader[$i]");
            $j = 2;
            //填充表格信息
            foreach ($data as $key => $val) {
                $objSheet->setCellValue("A" . $j, $val["liveId"])
                    ->setCellValue("B" . $j, $val["userId"])
                    ->setCellValue("C" . $j, $val["code"])
                    ->setCellValue("D" . $j, $val["createTime"])
                    ->setCellValue("E" . $j, $val["usedTime"])
                    ->setCellValue("F" . $j, $val["createTime"]);
                $j++;
            }
        }

        $write = new \PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename=' . $liveId . '活动邀请码.xls');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }

    public function test()
    {
        $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'F', 'G');
        static $letterArr = [];
        //获取大写字母
        for ($i = 65; $i < (64 + count($letter)); $i++) {
            $letterArr[] = strtoupper(chr($i));
            echo strtoupper(chr($i)) . ' ';
        }
        halt($letterArr);
    }

    /**
     * 根据活动好导出导出邀请码数据
     */
    public function readMysqlToExcelTable()
    {
        $liveId = "L02359";
        //创建对象
        $objPHPExcel = new \PHPExcel();
        //这里是根据Get过来的数组判断要导出的Excel的列数目
        $letterPost = ['11A', 'B11', 'C111', 'D1', 'D1', 'D1', 'D123423432', "weqweqw"];
        static $letter = [];
        //获取Excel 头部的大写字母
        for ($i = 65; $i < (64 + count($letterPost)); $i++) {
            $letter[] = strtoupper(chr($i));
        }

        //表头数组
        $tableHeader = ['活动ID', '用户昵称', '邀请码', '邀请码创建时间', '邀请码使用时间', '到期时间', "联系电话"];
        $objSheet = $objPHPExcel->getActiveSheet();   //获取当前活动sheet
        $objSheet->setTitle($liveId . " 活动邀请码");   //给当前活动sheet起个名称
        // 设置水平垂直居中
        $objSheet->getDefaultRowDimension()->setVisible(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // 设置默认字体
        $objSheet->getDefaultStyle()->getFont()->setSize(14)->setName("华文宋体");
        $objSheet->getStyle("A1:F1")->getFont()->setBold(true);
        //设置默认行高
        $objSheet->getDefaultRowDimension()->setRowHeight(20);
        //设置列的宽度
        $objSheet->getDefaultColumnDimension()->setWidth(20);
        $objSheet->getColumnDimension("A")->setWidth(10);
        $objSheet->getColumnDimension("B")->setWidth(10);
        $objSheet->getColumnDimension("C")->setWidth(10);

        //给表格添加数据
        for ($i = 0; $i < count($tableHeader); $i++) {
            //查询数据库
            $data = Db::table("resty_invitation_live_info")
                ->alias('l')
                ->join("resty_invitation i", "i.infoId = l.id")
                ->where("l.liveId", $liveId)
                ->select();
            //填充表头信息
            $objSheet->setCellValue("$letter[$i]1", "$tableHeader[$i]");
            $j = 2;
            //填充表格信息
            foreach ($data as $key => $val) {
                $objSheet->setCellValue("A" . $j, $val["liveId"])
                    ->setCellValue("B" . $j, $val["userId"])
                    ->setCellValue("C" . $j, $val["code"])
                    ->setCellValue("D" . $j, $val["createTime"])
                    ->setCellValue("E" . $j, $val["usedTime"])
                    ->setCellValue("F" . $j, $val["createTime"])
                    ->setCellValue("G" . $j, $val["tel"]);
                // 设置格式
                if ($val["userId"] != 0) {
                    // 设置字体颜色
                    $objSheet->getStyle("A" . $j . ":F" . $j)->getFont()->setUnderline(\PHPExcel_Style_Font::UNDERLINE_SINGLE)->getColor()->setARGB(\PHPExcel_Style_Color::COLOR_RED);
                    //设置背景颜色
//                    $objSheet->getStyle( "E" . $j)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB(\PHPExcel_Style_Color::COLOR_GREEN);
                }
                $j++;
            }
        }

        // 生成2007excel格式的xlsx文件
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition:attachment;filename=' . $liveId . '活动邀请码.xlsx');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }

    /**
     * 读取Excel 表格
     */
    public function readExcel()
    {
        //创建对象
        $excel = new \PHPExcel();
        $basePath = ROOT_PATH . 'public' . DS;
        $inputFileName = $basePath . 'uploads/L02359.xlsx';
        // Check if file exists
        if (empty($inputFileName) or !file_exists($inputFileName)) {
            return "file not exists";;
        }
        $fileType = \PHPExcel_IOFactory::identify($inputFileName);
        $objReader = new \PHPExcel_Reader_Excel2007($fileType);  //获取文件读取操作对象Excel2007
        if ($fileType == 'Excel5') {
            $objReader = new \PHPExcel_Reader_Excel5($fileType);    //Excel2003
        }
        $sheetName = array("L02359 活动邀请码", "市场部", "技术部", "研发部");    //需要读取的sheet名称
        $objReader->setLoadSheetsOnly("L02359 活动邀请码");    //只加载指定的sheet

        try {
            $objPHPExcel = $objReader->load($inputFileName);     //加载文件
        } catch (\PHPExcel_Reader_Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }

        // 一次性加载所有数据
        $sheetCount = $objPHPExcel->getSheetCount();    //获取excel文件里有多少个sheet
        for ($i = 0; $i < $sheetCount; $i++) {
            $data = $objPHPExcel->getSheet($i)->toArray();  //读取每个sheet里的数据 全部放入到数组中
//            print_r($data);
        }

        $dataArr = array();
        foreach ($objPHPExcel->getWorksheetIterator() as $sheet) //循环取sheet
        {
            foreach ($sheet->getRowIterator() as $row) {  //逐行处理
                foreach ($row->getCellIterator() as $cell) {  //逐列读取
                    $dataArr[] = $cell->getValue();    //获取单元格数据
                }
            }
        }
        halt($dataArr);
    }

    /**
     * 读取Excel 表格数据插入到Mysql数据库中去
     */
    public function readExcelInsertMysql($filePath, $sheet = 0)
    {
        // Check if file exists
        if (empty($filePath) or !file_exists($filePath)) {
            return "file not exists";;
        }
        $PHPReader = new \PHPExcel_Reader_Excel2007();  //获取文件读取操作对象Excel2007
        if (!$PHPReader->canRead($filePath)) {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if ($PHPReader->canRead($filePath)) {
                return "no Excel";
            }
        }

        try {
            //建立excel对象 加载文件
            $PHPExcel = $PHPReader->load($filePath);
            //**读取excel文件中的指定工作表*/
            $currentSheet = $PHPExcel->getSheet($sheet);
            //**取得最大的列号*/
            $allColumn = $currentSheet->getHighestColumn();
            //**取得一共有多少行*/
            $allRow = $currentSheet->getHighestRow();
            $dataArr = [];

            //循环读取每个单元格的内容。注意行从1开始，列从A开始
            for ($rowIndex = 1; $rowIndex <= $allRow; $rowIndex++) {
                for ($colIndex = 'A'; $colIndex <= $allColumn; $colIndex++) {
                    $addr = $colIndex . $rowIndex;
                    $cell = $currentSheet->getCell($addr)->getValue();
                    //富文本转换字符串
                    if ($cell instanceof PHPExcel_RichText) {
                        $cell = $cell->__toString();
                    }
                    $dataArr[$rowIndex][$colIndex] = $cell;
                }
            }
        } catch (\PHPExcel_Reader_Exception $e) {
            die('Error loading file "' . pathinfo($filePath, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }
        return $dataArr;
    }

    /**
     * 插入测试
     */
    public function readExcelInsertMysqlTest()
    {
        $basePath = ROOT_PATH . 'public' . DS;
        $filePath = $basePath . 'uploads/L02359.xlsx';
        $res = $this->readExcelInsertMysql($filePath);
        $telArr = [];
        foreach ($res as $key => $value) {
            $telArr[] = $value["G"];
        }
        //删除读取Excel表格的头部,剩下的全部为一个手机好的数组集合
        unset($telArr[0]);
        // 循环$telArr 手机号码数组，发送短信
        $req = new AlibabaAliqinFcSmsNumSend;
        foreach ($telArr as $key => $value) {
            $req->setRecNum($value)
                ->setSmsParam([
                    'number' => rand(100000, 999999) //邀请码
                ])
                ->setSmsFreeSignName('叶子坑')
                ->setSmsTemplateCode('SMS_15105357');
        }
        halt($telArr);
    }
}