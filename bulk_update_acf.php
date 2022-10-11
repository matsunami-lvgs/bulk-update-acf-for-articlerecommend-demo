<?php
/*
Plugin Name: BulkUpdateACF
Description: Advanced Castom Fields で 'article_recommend' と定義したカスタムフィードに対して、CSVデータを元に一括で変更する
*/
class BulkUpdateAcf
{
    public function __construct()
    {
        require_once(__DIR__ . '/update.php');
        require_once(__DIR__ . '/validator.php');
        require_once(__DIR__ . '/file.php');
        require_once(__DIR__ . '/result_interface.php');
        require_once(__DIR__ . '/failure.php');
        require_once(__DIR__ . '/success.php');

        add_action('admin_menu', function () {
            add_menu_page(
                '関連記事一括更新',
                '関連記事一括更新',
                'manage_options',
                'bulk_update_acf',
                array($this, 'view'),
            );
        });
        add_action('wp_ajax_bulk_update_acf_update', array($this, 'bulk_update_acf_update'));
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

    public function bulk_update_acf_update()
    {
        $file_path = $_FILES['userfile']['tmp_name'];
        $update = new BulkUpdateAcfUpdate($file_path);
        $result = $update->update();
        $result->sendMessage();
        exit;
    }
}

global $bulck_update_acf;
$bulck_update_acf = new BulkUpdateAcf();
