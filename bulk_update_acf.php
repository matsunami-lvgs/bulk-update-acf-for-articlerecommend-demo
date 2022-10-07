<?php
/*
Plugin Name: BulkUpdateACF
Description: Advanced Castom Fields で 'article_recommend' と定義したカスタムフィードに対して、CSVデータを元に一括で変更する
*/
class BulkUpdateAcf
{
    public function init()
    {
        require_once(__DIR__ . '/update.php');
        require_once(__DIR__ . '/file_validator.php');
        require_once(__DIR__ . '/result/result_interface.php');
        require_once(__DIR__ . '/result/failure.php');
        require_once(__DIR__ . '/result/success.php');

        add_action('admin_menu', function () {
            add_menu_page(
                '関連記事一括更新',
                '関連記事一括更新',
                'manage_options',
                'custom_menu_page',
                array($this, 'view'),
            );
        });
        add_action('wp_ajax_bulk_update_acf_update', array($this, 'post'));
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
        ほげ
        <div class="message error" id="bulk_update_acf_error" hidden></div>
        <div class="updated" id="bulk_update_acf_success" hidden></div>
        $script
        EOF;
    }

    public function post()
    {
        $file_path = $_FILES['userfile']['tmp_name'];
        try{
            if (!file_exists($file_path)) {
                wp_die('ファイルが存在しません。', '', 400);
            }
            $file = new SplFileObject($file_path);
            $update = new BulkUpdateAcfUpdate($file);
            $result = $update->update();
        } catch (Exception $e) {
            wp_die($e);
        }
            $result->sendMessage();
            exit;
    }
}

global $bulck_update_acf;
$bulck_update_acf = new BulkUpdateAcf;
$bulck_update_acf->init();
