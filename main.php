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
        'bu_acf_article_template',
    );
});

function bu_acf_article_template()
{
    $admin = admin_url('admin-ajax.php');
    $script = '<script>' . file_get_contents(__DIR__ . '/sample.js') . '</script>';
    echo <<<EOF
    <h1>関連記事一括更新</h1>
    <form enctype="multipart/form-data">
    <input type="hidden" name="action" value="bu_acf_article_update" />
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    このファイルをアップロード: <input name="userfile" type="file" id="bu_acf_file" />
    <p>
    <button type="button" onclick="post('$admin')">ファイルを送信</button>
    </p>
    </form>
    $script
    EOF;
}

//後でクラスにする
add_action('wp_ajax_bu_acf_article_update', 'bu_acf_article_update');
function bu_acf_article_update()
{
    //ファイルの中身を出力
    echo file_get_contents($_FILES['userfile']['tmp_name']) . PHP_EOL;
    //処理に失敗した場合はdie, 成功した場合はexitで返事
    $result = true;
    if (!$_FILES) {
        echo 'ファイルがありません';
        wp_die();
    }
    if (!$result) {
        echo 'エラー';
        wp_die();
    }
    echo '成功しました';
    exit;
}

//クラス化のメモ
/*
if ( true ) {
	add_action( 'admin_menu', array( 'Add_Quicktag', 'get_object' ) );
} else {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}*/
