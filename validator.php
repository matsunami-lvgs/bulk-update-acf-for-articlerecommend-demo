<?php
class BulkUpdateAcfValidator
{
    private $message_failure;
    private $path;

    public function __construct ($path) {
        $this->path = $path;
        $this->message_failure = new BulkUpdateAcfResultFailure();
    }

    public function validate()
    {
        if (!file_exists($this->path)) {
            $this->message_failure->addMessage('ファイルが存在しません。');
            return $this->message_failure;
        }
        $file = new BulkUpdateAcfFile($this->path);
        $csv_arr = $file->fetchCsv();
        $this->checkUniqueTargetId($csv_arr);
        $this->checkNaturalTargetId($csv_arr);
        $this->checkEmptyOrNaturalRelatedId($csv_arr);
        return $this->message_failure;
    }

    private function checkUniqueTargetId($csv_arr)
    {
        $target_ids = array_column($csv_arr, 'target_id');
        $dupicate_values = array_keys(array_filter(array_count_values($target_ids), function ($value) {
            return $value > 1;
        }));
        $duplicate_keys = array_filter($target_ids, function($target_id) use ($dupicate_values) {
            return in_array($target_id, $dupicate_values);
        });
        foreach(array_keys($duplicate_keys) as $duplicate_key) {
            $this->message_failure->addMessage('対象記事IDが重複しています。', $csv_arr[$duplicate_key]['line']);
        }
    }

    private function checkNaturalTargetId($csv_arr)
    {
        $target_ids = array_column($csv_arr, 'target_id');
        $not_nuturals = array_filter($target_ids, function ($target_id) {
            return !preg_match("/^[1-9][0-9]*$/", $target_id);
        });
        foreach(array_keys($not_nuturals) as $not_nutural_key) {
            $this->message_failure->addMessage('対象記事IDが空欄か不正な値です。', $csv_arr[$not_nutural_key]['line']);
        }
    }

    private function checkEmptyOrNaturalRelatedId($csv_arr)
    {
        $related_id_colmun = array_column($csv_arr, 'related_ids');
        $not_nuturals = array_filter($related_id_colmun, function ($related_ids) {
            foreach ($related_ids as $related_id) {
                return !preg_match("/^[1-9][0-9]*$|^$/", $related_id);
            }
        });
        foreach(array_keys($not_nuturals) as $not_nutural_key) {
            $this->message_failure->addMessage('関連記事IDが空欄か不正な値です。', $csv_arr[$not_nutural_key]['line']);
        }
    }
}
