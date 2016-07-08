<?php

if (!defined('BoluoExcel_ROOT')) {
    define('BoluoExcel_ROOT', dirname(__FILE__) . '/');
    require(BoluoExcel_ROOT . 'PHPExcel.class.php');
}

class BoluoExcel {

    var $FontStyle = array(
        'font' => array(
            'bold' => false,
            'color' => array('rgb' => '000000'),
            'size' => 8,
            'name' => '微软雅黑'
        ),
        'alignment' => array(
            'vertical' => "center",
            "horizontal" => "center"
        ),
    );
    var $TitleStyle = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
            'size' => 8,
            'name' => '微软雅黑'
        ),
        'alignment' => array(
            'vertical' => "center",
            "horizontal" => "center"
        ),
    );
    var $OutlineStyle = array(
        'borders' => array(
            'allborders' => array(
                'style' => "thin", //设置border样式
                'color' => array('argb' => 'FF666666'), //设置border颜色
            ),
        ),
    );
    var $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var $head = array();
    var $data = array();
    var $width = array();
    var $headmarge = FALSE;
    var $headHeight = 1;
    var $RowHeight = 20;

    function SetHead($Array) {
        $this->head = $Array;
        $this->headmarge = FALSE;
        return $this;
    }

    function DiyHead($Array) {
        $this->head = $Array;
        $this->headmarge = TRUE;
        return $this;
    }

    function SetData($Array) {
        $this->data = $Array;
        return $this;
    }

    function SetWidth($Array) {
        $this->width = $Array;
        return $this;
    }

    function Creat($FileName) {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
                ->setCreator("Boluo")
                ->setLastModifiedBy("Boluo")
                ->setTitle("Boluo");

        $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight($this->RowHeight);

        $widthcount = count($this->width);
        if ($widthcount > 0) {
            foreach ($this->width as $n => $num) {
                if ($num != "" || $num != null) {
                    $mk = self::getAZ($n);
                    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($mk)->setWidth($num);
                }
            }
        }

        $headcount = count($this->head);
        if ($headcount > 0) {
            if ($this->headmarge == FALSE) {
                for ($n = 0; $n < $headcount; $n++) {
                    $mic = self::getAZ($n) . "1";
                    $text = trim($this->head[$n]);
                    if (substr_count($text, "::") == 0) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($mic, $text);
                        $objPHPExcel->setActiveSheetIndex(0)->getStyle($mic)->applyFromArray($this->TitleStyle);
                    } else {
                        $textarr = explode("::", $text);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($mic, $textarr[0]);
                        $objPHPExcel->setActiveSheetIndex(0)->getStyle($mic)->applyFromArray($this->TitleColor($textarr[1]));
                    }
                }
            } else {
                foreach ($this->head as $mic => $headdata) {
                    $micarr = explode(":", $mic);
                    $text = trim($headdata);
                    if (substr_count($text, "::") == 0) {
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells($mic);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($micarr[0], $headdata);
                        $objPHPExcel->setActiveSheetIndex(0)->getStyle($micarr[0])->applyFromArray($this->TitleStyle);
                    } else {
                        $textarr = explode("::", $text);
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells($mic);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($micarr[0], $headdata);
                        $objPHPExcel->setActiveSheetIndex(0)->getStyle($micarr[0])->applyFromArray($this->TitleColor($textarr[1]));
                    }
                }
                $sheet = $objPHPExcel->getSheet(0);
                $this->headHeight = $sheet->getHighestRow();
            }
        }

        $datacount = count($this->data);
        if ($datacount > 0) {
            for ($i = 0; $i < $datacount; $i++) {
                $im = $i + $this->headHeight + 1;
                $dataline = $this->data[$i];
                $n = 0;
                foreach ($dataline as $data) {
                    $mic = self::getAZ($n) . $im;
                    $text = trim($data);
                    if (substr_count($text, "::") == 0) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($mic, $text);
                        $objPHPExcel->setActiveSheetIndex(0)->getStyle($mic)->applyFromArray($this->FontStyle);
                    } else {
                        $textarr = explode("::", $text);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($mic, $textarr[0]);
                        $objPHPExcel->setActiveSheetIndex(0)->getStyle($mic)->applyFromArray($this->FontColor($textarr[1]));
                    }
                    $n++;
                }
            }
        }

        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:' . $mic)->applyFromArray($this->OutlineStyle);
        $objPHPExcel->getActiveSheet()->setTitle("内容");
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($FileName);
    }

    function Save($FileName) {
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load($FileName);

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();           //取得总行数
        $highestColumn = $sheet->getHighestColumn(); //取得总列数

        $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight($this->RowHeight);

        $widthcount = count($this->width);
        if ($widthcount > 0) {
            foreach ($this->width as $n => $num) {
                if ($num != "" || $num != null) {
                    $mk = self::getAZ($n);
                    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($mk)->setWidth($num);
                }
            }
        }

        $datacount = count($this->data);
        if ($datacount > 0) {
            for ($i = 0; $i < $datacount; $i++) {
                $im = $i + $highestRow + 1;
                $dataline = $this->data[$i];
                $n = 0;
                foreach ($dataline as $data) {
                    $mic = self::getAZ($n) . $im;
                    $text = trim($data);
                    if (substr_count($text, "::") == 0) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($mic, $text);
                        $objPHPExcel->setActiveSheetIndex(0)->getStyle($mic)->applyFromArray($this->FontStyle);
                    } else {
                        $textarr = explode("::", $text);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($mic, $textarr[0]);
                        $objPHPExcel->setActiveSheetIndex(0)->getStyle($mic)->applyFromArray($this->FontColor($textarr[1]));
                    }
                    $n++;
                }
            }
        }

        echo $highestRow;
        echo "<br/>";
        return;

        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:' . $mic)->applyFromArray($this->OutlineStyle);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($FileName);
    }

    static function getAZ($n) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $xn = $n % 26;
        $bn = ($n - $xn) / 26 - 1;
        if ($bn < 0) {
            $m1 = "";
        } else {
            $m1 = $chars[$bn];
        }
        $m2 = $chars[$xn];
        $mm = $m1 . $m2;
        return $mm;
    }

    function TitleColor($color) {
        $TitleStyle = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => $color),
                'size' => 8,
                'name' => '微软雅黑'
            ),
            'alignment' => array(
                'vertical' => "center",
                "horizontal" => "center"
            ),
        );
        return $TitleStyle;
    }

    function FontColor($color) {
        $FontStyle = array(
            'font' => array(
                'bold' => false,
                'color' => array('rgb' => $color),
                'size' => 8,
                'name' => '微软雅黑'
            ),
            'alignment' => array(
                'vertical' => "center",
                "horizontal" => "center"
            ),
        );
        return $FontStyle;
    }

}

?>
