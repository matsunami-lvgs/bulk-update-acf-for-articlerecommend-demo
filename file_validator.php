<?php
class BulkUpdateAcfValidator
{
    private $file;
    private $message_failure;

    public function __construct (SplFileObject $file) {
        $this->file = $file;
        $this->message_failure = new BulkUpdateAcfResultFailure();
    }

    public function validate()
    {
        $csv_arr = $this->fetchCsv();
        $this->checkNaturalPostsIds($csv_arr);
        $this->checkDuplicatelPostsIds($csv_arr);
        $this->checkNullableNaturalTargetIds($csv_arr);
        return $this->message_failure;
    }

    private function fetchCsv()
    {
        $this->file->seek(1);
        for ($i = 2; !$this->file->eof(); $i++) {
            $csv_arr[$i] = $this->file->fgetcsv();
        }
        return $csv_arr;
    }

    private function checkDuplicatelPostsIds($csv_arr)
    {
        $post_ids = array_column($csv_arr, 0);
        $unique_diff = array_diff($post_ids, array_unique($post_ids));
        if ($unique_diff) {
            $diff_arrays = array_filter($post_ids, function($value) use ($unique_diff) {
                return in_array($value, $unique_diff);
            });
            foreach ($diff_arrays as $key => $value) {
                $this->message_failure->addMessage("{$key} 行目の登録対象記事IDが他の行と重複しています。");
            }
        }
    }

    private function checkNaturalPostsIds($csv_arr)
    {
        $post_ids = array_column($csv_arr, 0);
        $null_post_ids = array_filter($post_ids, function ($value){
            return !preg_match("/^[1-9][0-9]+$/", $value);
        });
        foreach ($null_post_ids as $key => $value) {
            $this->message_failure->addMessage("{$key} 行目の登録対象記事IDが空欄か不正な値です。");
        }
    }

    private function checkNullableNaturalTargetIds($csv_arr)
    {
        foreach($csv_arr as $kobetu_key => $kobetu_arr) {
            $hoge = array_filter($kobetu_arr, function ($key, $value) {
                return $key !== 0 &&
                !empty($value) &&
                !preg_match("/^[1-9][0-9]+$/", $value);
            }, ARRAY_FILTER_USE_BOTH);
            if ($hoge){
                $this->message_failure->addMessage("{$kobetu_key} 行目の関連記事IDが不正な値です。");
            }
        }
    }
}
