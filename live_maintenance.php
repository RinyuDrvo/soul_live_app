<?php

// //DB設定読み込み
// require_once __DIR__ . '/conf/database_conf.php';
require_once __DIR__ . '/conf/heroku_database_conf.php';

// //h()関数読み込み
require_once __DIR__ . '/lib/h.php';

try {

    //DB接続
    $db = new PDO("mysql:host=$dbServer;dbname=$dbName;charset=utf8","$dbUser","$dbPass");
    $db ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //削除ボタンが押されたら
    if (isset($_POST['live_delete'])) {
        //ライブレコード削除
        //削除するlive_idを取得
        $live_id = $_POST['live_id'];
        //SQL準備
        $sql = "DELETE FROM live WHERE live_id = :live_id";
        $prepare = $db -> prepare($sql);
        //live_idバインド
        $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare -> execute();

        //そのライブのバンドレコード削除
        //SQL準備
        $sql = "DELETE FROM band WHERE live_id = :live_id";
        $prepare = $db -> prepare($sql);
        //live_idバインド
        $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare -> execute();

        //そのライブのformation情報削除
        //SQL準備
        $sql = "DELETE FROM formation
            WHERE band_id IN (SELECT band_id FROM band WHERE live_id = :live_id)";
        $prepare = $db -> prepare($sql);
        //live_idバインド
        $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare -> execute();

        //結果表示
        echo "<p>消去成功</p>";

    }

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ライブメンテナンス</title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
        <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
		<link rel="stylesheet" href="https://milligram.github.io/styles/main.css">
    </head>
    <body>
        <main class="wrapper">
            <section class="container" id="tables">
                <h2 class="title">ライブメンテナンス</h2>
                <h1 class="title">ライブ一覧</h1>
                <div class="example">
                    <table>
                        <thead><th>ライブID</th><th>ライブ</th><th></th></thead>
                        <tbody>

<?php

    //SQL準備
    $sql = 'SELECT * FROM live ORDER BY live_id';
    $prepare = $db->prepare($sql);
    //クエリ実行
    $prepare->execute();

    //ライブ名をひとつずつrowに設定
    foreach ($prepare as $row) {
?>

                            <tr>
                                <td>
                                    <!--ライブID表示-->
                                    <?= h($row['live_id']) ?>
                                </td>
                                <td>
                                    <!--ライブ名表示-->
                                    <?= h($row['live_name']) ?>
                                </td>
                                <td>
                                    <!--削除ボタン表示 POSTメソッドでlive_idを削除部分に渡す-->
                                    <form method="POST">
                                        <input type="submit" name="live_delete" value="delete">
                                        <input type="hidden" name="live_id" value="<?= h($row['live_id']) ?>">
                                    </form>
                                </td>
                            </tr>

<?php
    }
}catch (PDOException $e) {
    echo 'データベースエラー発生：' . h($e->getMessage());
}catch (Exception $e){
    echo 'エラー発生' . h($e->getMessage());
}
?>

                        </tbody>
                    </table>
                </div>
                <p>
                    <a href="live_insert.php">
                        ライブ追加
                    </a>
                </p>
                <p>
                    <a href="index.php">
                        戻る
                    </a>
                </p>
            </section>
        </main>
    </body>
</html>