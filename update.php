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
        $sql = $this->execute();
        global $wpdb;
        $wpdb->query($sql);
        return new BulkUpdateAcfResultSuccess('成功しました。');
    }

    private function execute()
    {
        //ヒアドキュメント内で定数を展開できないため定数の代わりを変数で定義
        $file = new BulkUpdateAcfFile($this->file_path);
        $csv = $file->fetchCsv();
        $table = 'wp_postmeta';
        $columns = 'meta_value, post_id, meta_key';
        //各環境でチェック
        $article_recomended = 'article_recommend';
        $under_article_recomended = '_article_recommend';
        $under_article_recomended_meta_value = 'field_606464c813bc6';
        
        $target_ids = implode("', '", array_column($csv, 'target_id'));
        global $wpdb;
        $delete_query = <<<EOM
        DELETE FROM
            $table
        WHERE
            meta_key IN ('$article_recomended' ,'$under_article_recomended') AND
            post_id IN ('$target_ids');
        EOM;
        $wpdb->query($delete_query);

        $insert_values_arr = [];
        foreach ($csv as $line) {
            $target_id = $line['target_id'];
            $serialized_related_ids = serialize($line['related_ids']);
            $insert_values_arr[] = <<<EOM
            ('$serialized_related_ids', '$target_id', '$article_recomended'),
            ('$under_article_recomended_meta_value', '$target_id', '$under_article_recomended')
            EOM;
        }
        $insert_values = implode(',', $insert_values_arr);
        $insert_query = <<<EOM
        INSERT INTO
            $table ($columns)
        VALUES 
            $insert_values
        EOM;
        $wpdb->query($insert_query );
    }
}
