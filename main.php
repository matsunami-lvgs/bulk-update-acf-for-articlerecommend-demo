<?php
/*
Plugin Name: BulkUpdateACF for ArticleRecommend
Description: Advanced Castom Fields で 'article_recommend' と定義したカスタムフィードに対して、CSVデータを元に一括で変更する
*/


add_action('admin_menu', function () {
    add_menu_page(
        '関連記事一括更新',
        '関連記事一括更新',
        'manage_options',
        'custom_menu_page',
        'bulk_update_acf_article_template',
    );
});

function bulk_update_acf_article_template()
{
    $admin = admin_url('admin-ajax.php');
    $script = '<script>' . file_get_contents(__DIR__ . '/sample.js') . '</script>';
    echo <<<EOF
    <h1>関連記事一括更新</h1>
    <form enctype="multipart/form-data">
        <input type="hidden" name="action" value="bulk_update_acf_article_update" />
        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
        このファイルをアップロード: <input name="userfile" type="file" id="bulk_update_acf_file" />
        <p>
            <button type="button" onclick="post('$admin')">ファイルを送信</button>
        </p>
    </form>
    <div class="message error" id="bulk_update_acf_error" hidden></div>
    <div class="updated" id="bulk_update_acf_updated" hidden></div>
    $script
    EOF;
}

//後でクラスにする
add_action('wp_ajax_bulk_update_acf_article_update', 'bulk_update_acf_article_update');
function bulk_update_acf_article_update()
{
    if (!$_FILES) {
        wp_die('ファイルがありません', '', ['response' => 400]);
    }
    $result = true;
    if (!$result) {
        wp_die('エラー', '', ['response' => 500]);
    }
    echo '成功しました。';
    exit;
}
