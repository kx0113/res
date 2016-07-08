<?php

/**
 * �����ռ�
 */
namespace Common\ORG\Util;

/**
 * ��Excelת��������
 */
class ExcelToArray {

  public function __construct() {
		Vendor("PHPExcel.PHPExcel");//����phpexcel��(ע�����Լ���·��)
		Vendor("PHPExcel.PHPExcel.IOFactory"); 	
  }
  
  /**
   * ��ȡ�ļ�
   */
  public function read($filename,$encode,$file_type){
    // �ж�excel������Ϊ2003����2007
    if (strtolower ( $file_type )=='xls') {
      Vendor("PHPExcel.PHPExcel.Reader.Excel5"); 
      $objReader = \PHPExcel_IOFactory::createReader('Excel5');
    }
    elseif (strtolower( $file_type )=='xlsx') {
      Vendor("PHPExcel.PHPExcel.Reader.Excel2007"); 
      $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
    }
    $objReader->setReadDataOnly(true);
    $objPHPExcel        = $objReader->load($filename);
    $objWorksheet       = $objPHPExcel->getActiveSheet();
    $highestRow         = $objWorksheet->getHighestRow();
    $highestColumn      = $objWorksheet->getHighestColumn();
    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
    $excelData = array();
    for ($row = 1; $row <= $highestRow; $row++) {
      for ($col = 0; $col < $highestColumnIndex; $col++) {
        $excelData[$row][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
      }
    }
    return $excelData;
  }
}


?>