<?php
class BulkUpdateAcfValidator
{
    private $irr;
    public static function validation ()
    {
        $error[];

        return  new BulkUpdateAcfResult(true, 200, '成功しました');
    }

    public function getIrr()
    {
        return $this->irr;
    }

    private function fetchCsv()
    {

    }

    private function 
}
