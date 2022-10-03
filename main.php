<?php
/*
Plugin Name: BulkUpdateACF for ArticleRecommend
Description: Advanced Castom Fields で 'article_recommend' と定義したカスタムフィードに対して、CSVデータを元に一括で変更する
*/
class BulkUpdateAcf
{
    public function init()
    {
        add_action('admin_menu', function () {
            add_menu_page(
                '関連記事一括更新',
                '関連記事一括更新',
                'manage_options',
                'custom_menu_page',
                array($this, 'view'),
            );
        });
        add_action('wp_ajax_bulk_update_acf_update', array($this, 'update'));
    }

    public function view()
    {
        $admin = admin_url('admin-ajax.php');
        $script = '<script>' . file_get_contents(__DIR__ . '/sample.js') . '</script>';
        echo <<<EOF
        <h1>関連記事一括更新</h1>
        <form enctype="multipart/form-data">
            このファイルをアップロード: <input name="userfile" type="file" id="bulk_update_acf_file" accept="text/csv"/>
            <p>
                <button type="button" onclick="post('$admin')">ファイルを送信</button>
            </p>
        </form>
        <div class="message error" id="bulk_update_acf_error" hidden></div>
        <div class="updated" id="bulk_update_acf_success" hidden></div>
        $script
        EOF;
    }
    public function update()
    {
        include(__DIR__ . '/update.php');
        $update = new BulkUpdateAcfUpdate;
        echo 'ほげ';
        echo var_dump($update->file);
        echo $update->file->fgets();
        //$update->update();
        exit;
    }
}

global $bulck_update_acf;
$bulck_update_acf = new BulkUpdateAcf;
$bulck_update_acf->init();
