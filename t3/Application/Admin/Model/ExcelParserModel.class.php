<?php
namespace Mam\Model;

use Think\Model;

/**
 * 聚合节目库模型
 */
class ExcelParserModel extends Model
{

    //导入execl//
    public function cardSave($filename, $encode = 'utf-8')
    {
        require_once './Public/PHPExcel/PHPExcel/IOFactory.php';
        require_once './Public/PHPExcel/PHPExcel/Cell.php';
        $objPHPExcel = \PHPExcel_IOFactory::load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col=0; $col<$highestColumnIndex; $col++) {
                $excelData[] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        $this->saveImport($excelData);
    }


    /**
     * 生成操作日志消息
     * @param  array $updateData 更新数据
     * @param  array $oriData 未更新前数据库数据
     * @return string 处理结果
     */
    private function __getOperationMsg($updateData, $oriData)
    {
        // 仅记录传递有变化的字段
        foreach ($updateData as $k => $v) {
            if ($v == $oriData[$k]) {
                unset($updateData[$k]);
            }
        }
        $returnMsg = '';
        foreach ($updateData as $k => $v) {
            $returnMsg .= '  字段名：' . $k . '  修改前：' . $oriData[$k] . '  修改后：' . $v;
        }
        return $returnMsg;
    }
}
