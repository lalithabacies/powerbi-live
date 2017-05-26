<?php

namespace app\components;
use moonland\phpexcel\Excel;

class PBI_Excel extends Excel
{

	/**
	 * reading the xls file
	 */
	public function readFile($fileName)
	{
		if (!isset($this->format))
			$this->format = \PHPExcel_IOFactory::identify($fileName);
		$objectreader = \PHPExcel_IOFactory::createReader($this->format);
		$objectPhpExcel = $objectreader->load($fileName);
		
		$sheetCount = $objectPhpExcel->getSheetCount();
		
		$sheetDatas = [];
		
		//if ($sheetCount > 1) {
			foreach ($objectPhpExcel->getSheetNames() as $sheetIndex => $sheetName) {
				$objectPhpExcel->setActiveSheetIndexByName($sheetName);
				$indexed = $this->setIndexSheetByName==true ? $sheetName : $sheetIndex;
				$sheetDatas[$indexed] = $objectPhpExcel->getActiveSheet()->toArray(null, true, true, true);
				if ($this->setFirstRecordAsKeys) {
					$sheetDatas[$indexed] = $this->executeArrayLabel($sheetDatas[$indexed]);
				}
				if (!empty($this->getOnlyRecordByIndex) && isset($this->getOnlyRecordByIndex[$indexed]) && is_array($this->getOnlyRecordByIndex[$indexed])) {
					$sheetDatas = $this->executeGetOnlyRecords($sheetDatas, $this->getOnlyRecordByIndex[$indexed]);
				}
				if (!empty($this->leaveRecordByIndex) && isset($this->leaveRecordByIndex[$indexed]) && is_array($this->leaveRecordByIndex[$indexed])) {
					$sheetDatas[$indexed] = $this->executeLeaveRecords($sheetDatas[$indexed], $this->leaveRecordByIndex[$indexed]);
				}
			}
			if (isset($this->getOnlySheet) && $this->getOnlySheet != null) {
				$indexed = $this->setIndexSheetByName==true ? $this->getOnlySheet : $objectPhpExcel->getIndex($objectPhpExcel->getSheetByName($this->getOnlySheet));
				return $sheetDatas[$indexed];
			}
		//} 
/* 		else {
			$sheetDatas = $objectPhpExcel->getActiveSheet()->toArray(null, true, true, true);
			if ($this->setFirstRecordAsKeys) {
				$sheetDatas = $this->executeArrayLabel($sheetDatas);
			}
			if (!empty($this->getOnlyRecordByIndex)) {
				$sheetDatas = $this->executeGetOnlyRecords($sheetDatas, $this->getOnlyRecordByIndex);
			}
			if (!empty($this->leaveRecordByIndex)) {
				$sheetDatas = $this->executeLeaveRecords($sheetDatas, $this->leaveRecordByIndex);
			}
		} */
		
		return $sheetDatas;
	}
	
}