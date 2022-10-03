<?php
class BulkUpdateAcfUpdate
{
    private $validator;
    private $querymaker;
    public $file;

    public function __construct()
    {
        //$this->validator = new BulkUpdateAcfValidation;
        //$this->querymaker = new BulkUpdateAcfQueryMaker;
        $this->file = new SplFileObject($_FILES['userfile']['tmp_name']);
    }
    public function update()
    {
        $this->validator->hoge();
    }

    private function execute_query($query)
    {
        $wpdb->query($query);
    }
}

$arr = [
    [
        'post_id' => 10,
        'recomend_posts' => [12, 11, 45, 56]
    ],
    [
        'post_id' => 10,
        'recomend_posts' => [12, 11, 45, 56]
    ],
    [
        'post_id' => 10,
        'recomend_posts' => [12, 11, 45, 56]
    ],
    [
        'post_id' => 10,
        'recomend_posts' => [12, 11, 45, 56]
    ],
];
