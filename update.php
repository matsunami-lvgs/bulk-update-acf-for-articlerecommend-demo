<?php
class BulkUpdateAcfUpdate
{
    private $validator;
    private $file_path;

    public function __construct($file_path)
    {
        $this->file_path = $file_path;
        $this->validator = new BulkUpdateAcfValidator($file_path);
    }

    public function update()
    {
        $validate_result = $this->validator->validate();
        if ($validate_result->isFailured()) {
            return $validate_result;
        }
        $this->execute();
        return new BulkUpdateAcfResultSuccess('成功しました。');
    }

    private function execute()
    {
        $file = new BulkUpdateAcfFile($this->file_path);
        $query_origin = $file->fetchCsv();
        //ヒアドキュメント内で定数を展開できないため変数の配列で定義
        $recomend_attributes = [
            'name' => 'article_recommend',
            'under_name' => 'article_recommend',
            'meta_value' => 'field_606464c813bc6',
        ];
        
        global $wpdb;
        $delete_query = $this->makeDeleteQuery($query_origin, $recomend_attributes);
        $wpdb->query($delete_query);
        $insert_query = $this->makeInsertQuery($query_origin, $recomend_attributes);
        $wpdb->query($insert_query );
    }

    private function makeDeleteQuery($query_origin, $recomend_attributes)
    {
        $target_ids = implode("', '", array_column($query_origin, 'target_id'));
        return <<<EOM
        DELETE FROM
            wp_postmeta
        WHERE
            meta_key IN ('{$recomend_attributes['name']}' ,'{$recomend_attributes['under_name']}') AND
            post_id IN ('$target_ids');
        EOM;
    }

    private function makeInsertQuery($query_origin, $recomend_attributes)
    {
        $insert_values_arr = [];
        foreach ($query_origin as $line) {
            $target_id = $line['target_id'];
            $serialized_related_ids = serialize($line['related_ids']);
            $insert_values_arr[] = <<<EOM
            ('$serialized_related_ids', '$target_id', '{$recomend_attributes['name']}' ),
            ('{$recomend_attributes['meta_value']}', '$target_id', '{$recomend_attributes['under_name']}')
            EOM;
        }
        $insert_values = implode(',', $insert_values_arr);
        return <<<EOM
        INSERT INTO
            wp_postmeta (meta_value, post_id, meta_key)
        VALUES 
            $insert_values;
        EOM;

    }
}
