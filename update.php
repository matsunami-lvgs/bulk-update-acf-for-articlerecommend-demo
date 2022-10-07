<?php
class BulkUpdateAcfUpdate
{
    private $validator;
    private $file;

    public function __construct(SplFileObject $file)
    {
        $this->file = $file;
        $this->validator = new BulkUpdateAcfValidator($file);
    }

    public function update():BulkUpdateAcfResult
    {
        $validate_result = $this->validator->validate();
        if ($validate_result->isFailured()) {
            return $validate_result;
        };
        $sql = $this->makeSql($this->file);
        $wpdb->query($sql);
        return new BulkUpdateAcfResultSuccess();
    }

    private function formatFile()
    {
        $formatted = [];
        $this->file->seek(1);
        for ($i = 1; !$this->file->eof(); $i++) {
            $row = $this->file->fgetcsv();
            $post_id = $row[0];
            foreach ($row as $key => $value) {
                if ($post_id == $value) {
                    unset($row[$key]);
                }
            }
            $recomend_posts = array_values($row);

            if ($post_id && $recomend_posts) {
                $formatted[$post_id] = $recomend_posts;
            }
        }
        return $formatted;
    }

    private function makeSql () {
        //ヒアドキュメント内で定数を展開できないため定数の代わりを変数で定義
        $formatted = $this->formatFile();
        $table = 'wp_postmeta';
        $columns = 'meta_value, post_id, meta_key';
        //各環境でチェック
        $article_recomended = 'article_recommend';
        $under_article_recomended = '_article_recommend';
        $under_article_recomended_meta_value = 'field_606464c813bc6';

        $sql = "BEGIN;\n";
        foreach ($formatted as $post_id => $recomend_posts) {
            $serialized = serialize($recomend_posts);
            $delete_and_insert = <<<EOM
            DELETE FROM 
                $table
            WHERE
                meta_key = '$article_recomended'
                AND post_id = '$post_id';

            DELETE FROM 
                $table
            WHERE
                meta_key = '$under_article_recomended'
                AND post_id = '$post_id';

            INSERT INTO
                $table ($columns)
            VALUES 
                (
                    '$serialized',
                    '$post_id',
                    '$article_recomended'
                );

            INSERT INTO
                $table ($columns)
            VALUES
                (
                    '$under_article_recomended_meta_value',
                    '$post_id',
                    '$under_article_recomended'
                );\n\n
            EOM;
            $sql .= $delete_and_insert;
        }
        $sql .= "COMMIT;\n";
        return $sql;
    }
}
