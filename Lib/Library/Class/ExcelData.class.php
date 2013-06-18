<?php
        //================================================
        //   功能：Excel的导入导出
        //   作者：武仝
        //   日期：2012-09-03
        //   版本：1.1
        //   更新：
        //   2012-10-18 增加了导入的时候提供一个标识索引，用来更好的引用数据
        //================================================
        class ExcelData{
                //=====================
                //   单元格头
                //=====================
                static protected $cellHead=array(
                        "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
                        "AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ"
                );
                //=====================
                //   根据字母得到列数
                //=====================
                static protected $cellLen=array(
                        "A"=>1,"B"=>2,"C"=>3,"D"=>4,"E"=>5,"F"=>6,"G"=>7,"H"=>8,"I"=>9,"J"=>10,"K"=>11,"L"=>12,"M"=>13,"N"=>14,"O"=>15,"P"=>16,"Q"=>17,"R"=>18,"S"=>19,"T"=>20,"U"=>21,"V"=>22,"W"=>23,"X"=>24,"Y"=>25,"Z"=>26,
                        "AA"=>27,"AB"=>28,"AC"=>29,"AD"=>30,"AE"=>31,"AF"=>32,"AG"=>33,"AH"=>34,"AI"=>35,"AJ"=>36,"AK"=>37,"AL"=>38,"AM"=>39,"AN"=>40,"AO"=>41,"AP"=>42,"AQ"=>43,"AR"=>44,"AS"=>45,"AT"=>46,"AU"=>47,"AV"=>48,"AW"=>49,"AX"=>50,"AY"=>51,"AZ"=>52
                );
                //=====================
                //   根据1900返回的整数进行转换成0000-00-00
                //   @param	string			days
                //   @param	boolean		time
                //=====================
                static public function excelTime($days, $time=false){
                        if(is_numeric($days)){
                                $jd = GregorianToJD(1, 1, 1970);
                                $gregorian = JDToGregorian($jd+intval($days)-25569);
                                $myDate = explode('/',$gregorian);
                                $myDateStr = str_pad($myDate[2],4,'0', STR_PAD_LEFT)
                                ."-".str_pad($myDate[0],2,'0', STR_PAD_LEFT)
                                ."-".str_pad($myDate[1],2,'0', STR_PAD_LEFT)
                                .($time?" 00:00:00":'');
                                return $myDateStr;
                        }
                        return $days;
                }
                //=====================
                //   导出为Excel
                //   @param	string[]		data
                //   @param	string[]		field
                //   @param	string[]		option
                //=====================
                static public function exportData($data,$field=NULL,$option=NULL){
                        import("@.Library.PHPExcel.PHPExcel");
                        $PHPExcel = new PHPExcel();
                        $PHPExcel->setActiveSheetIndex(0);
                        $keys=array_keys($data[0]);
                        //配置Excel
                        $PHPExcel->getActiveSheet()->getRowDimension()->setRowHeight(21);
                        $PHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
                        //起始行
                        $rowStart=1;
                        //文件名
                        $fileName=date("YmdHis");
                        //设置标题
                        if(is_array($option)){
                                if(isset($option['title'])){
                                        $PHPExcel->getActiveSheet()->mergeCells('A1:'.self::$cellHead[count($keys)-1].'1');
                                        $PHPExcel->getActiveSheet()->setCellValue('A1',$option['title']);
                                        self::setExcelTitleStyle($PHPExcel,$rowStart);
                                        $rowStart++;
                                }
                                if(isset($option['fileName'])){
                                        $fileName=$option['fileName'];
                                }
                        }
                        //设置列名
                        if(is_array($field)){
                                for($i=0;$i<count($field);$i++):
                                        $PHPExcel->getActiveSheet()->setCellValue(self::$cellHead[$i].$rowStart,$field[$i]);
                                        if($option['fieldWidth']){
                                                $PHPExcel->getActiveSheet()->getColumnDimension(self::$cellHead[$i])->setWidth($option['fieldWidth'][$i]);
                                        }
                                        endfor;
                                self::setExcelColumnHeadStyle($PHPExcel,$rowStart);
                                $rowStart++;
                        }
                        //设置数据
                        for($i=0;$i<count($data);$i++):
                                for($j=0;$j<count($keys);$j++):
                                        $PHPExcel->getActiveSheet()->setCellValue(self::$cellHead[$j].($rowStart),$data[$i][$keys[$j]]);
                                        endfor;
                                $rowStart++;
                                endfor;
                        //设置样式
                        self::setExcelStyle($PHPExcel);

                        //导出数据
                        ob_clean();
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
                        header('Cache-Control: max-age=0');
                        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
                        $objWriter->save('php://output');
                        return;
                }
                //=====================
                //   设置Excel标题样式
                //   @param	object[]		PHPExcel
                //   @param	int			rowNum
                //=====================
                static protected function setExcelTitleStyle($PHPExcel){
                        $rowArray=array(
                                'font' => array(
                                        'size' => 18,
                                        'name'=>'微软雅黑'
                                ),
                                'fill' => array(
                                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                        'rotation' => 90,
                                        'startcolor' => array(
                                                'argb' => 'FFFEFEFE',
                                        ),
                                        'endcolor' => array(
                                                'argb' => 'FFDEDEDE',
                                        ),
                                ),
                                'alignment' => array(
                                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                )
                        );
                        $cellCount=$PHPExcel->getActiveSheet()->getHighestColumn();
                        $PHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
                        $PHPExcel->getActiveSheet()->getStyle('A1:A1')->applyFromArray($rowArray);

                }
                //=====================
                //   设置Excel列表头样式
                //   @param	object[]		PHPExcel
                //   @param	int			rowNum
                //=====================
                static protected function setExcelColumnHeadStyle($PHPExcel,$rowNum){
                        $rowArray=array(
                                'borders' => array(
                                        'allborders' => array(
                                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                'color' => array('argb' => 'FF666666'),
                                        ),
                                ) ,
                                'font' => array(
                                        'size' => 10,
                                        'bold' => true,
                                        'name'=>'微软雅黑'
                                ),
                                'fill' => array(
                                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                        'rotation' => 90,
                                        'startcolor' => array(
                                                'argb' => 'FFEEEEEE',
                                        ),
                                        'endcolor' => array(
                                                'argb' => 'FFFFFFFF',
                                        ),
                                ),

                        );
                        $cellCount=$PHPExcel->getActiveSheet()->getHighestColumn();
                        $PHPExcel->getActiveSheet()->getStyle('A'.$rowNum.':'.$cellCount.$rowNum)->applyFromArray($rowArray);
                }
                //=====================
                //   设置Excel样式
                //   @param	object[]		PHPExcel
                //=====================
                static protected function setExcelStyle($PHPExcel){
                        //所有内容加边框
                        $styleArray = array(
                                'borders' => array(
                                        'allborders' => array(
                                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                'color' => array('argb' => 'FF666666'),
                                        ),
                                ) ,
                                'font' => array(
                                        'size' => 9,
                                        'name'=>'微软雅黑'
                                ),
                                'alignment' => array(
                                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                )
                        );
                        $rowCount=$PHPExcel->getActiveSheet()->getHighestRow();
                        $cellCount=$PHPExcel->getActiveSheet()->getHighestColumn();
                        $PHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
                        $PHPExcel->getActiveSheet()->getStyle('A2:'.$cellCount.$rowCount)->applyFromArray($styleArray);
                }
                //=====================
                //   导入Excel
                //   @param	string		file       文件路径
                //   @param	int		indexRow          开始行
                //   @param     array          field           字段
                //   @param	int		activeSheet        指定是哪个表
                //=====================
                static public function importData($file,$indexRow=1,$field=null,$activeSheet=0){
                        import("@.Library.PHPExcel.PHPExcel");
                        $PHPReader = new PHPExcel_Reader_Excel5();
                        $PHPExcel = $PHPReader->load($file);
                        $currentSheet  = $PHPExcel->getSheet($activeSheet);
                        //声明数据
                        $data=array();
                        //取得一共有多少列
                        $allColumn = $currentSheet->getHighestColumn();
                        var_dump($allColumn);
                        //取得一共有多少行
                        $allRow = $currentSheet->getHighestRow();
                        //起始数据索引
                        $arrIndex=0;
                        //列数 int
                        $cellCount=self::$cellLen[$allColumn];
                        for($row=$indexRow;$row<=$allRow;$row++):
                                //获取每列的数据
                                for($cell=0;$cell<$cellCount;$cell++):
                                        //var_dump($currentSheet->getCell(self::$cellHead[$cell].$row)->getValue());
                                        $__field=$field==null ? $cell : $field[$cell];
                                        $data[$arrIndex][$__field]=addslashes(trim($currentSheet->getCell(self::$cellHead[$cell].$row)->getValue()));
                                        endfor;
                                $arrIndex++;
                                endfor;
                        return $data;
                }
}