<?php
class BulkUpdateAcfUpdate
{
    private $validator;
    private $querymaker;

    public function __construct()
    {
        //$this->querymaker = new BulkUpdateAcfQueryMaker;
        $this->validator = new BulkUpdateAcfValidator;
    }
    public function update()
    {
        try {
            $file = new SplFileObject($_FILES['userfile']['tmp_name']);
        } catch (Exception $e) {
            return new BulkUpdateAcfResult(false, 'ファイルが存在しません。', 400);
        }


        $result = $this->validator->validation();//result系のクラス
        if (!$result->getStatus()){
            return $result;
        }
        $this->validator->getIrr();
        return $result;
    }

    private function execute_query($query)
    {
        $wpdb->query($query);
    }
}
