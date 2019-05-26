<?php

    // //DB設定読み込み
    require_once __DIR__ . '/conf/database_conf.php';

    // //h()関数読み込み
    require_once __DIR__ . '/lib/h.php';

    //DB接続
    $db = new PDO("mysql:host=$dbServer;dbname=$dbName;charset=utf8","$dbUser","$dbPass");
    $db ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    try {
        //削除ボタンが押されたら
        if (isset($_POST['live_delete'])) {
            //削除するtodoのidを取得
            $live_id = $_POST['live_id'];
            //SQL準備(idのレコードを削除)
            $sql = "DELETE FROM live WHERE live_id = :live_id";
            $prepare = $db -> prepare($sql);
            //idに挿入する変数と型を指定
            $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
            //クエリ実行
            $prepare -> execute();
        }
    } catch (PDOException $e) {
        echo 'エラー発生：' . h($e->getMessage());
    }

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ライブメンテナンス</title>
    </head>
    <body>
        <h1>ライブメンテナンス</h1>

        <h2>ライブ名</h2>
        <table>
            <thead><th>ライブ</th><th></th></thead>
            <tbody>

<?php

// //DB設定読み込み
// require_once __DIR__ . '/conf/database_conf.php';

// //h()関数読み込み
// require_once __DIR__ . '/lib/h.php';

// //DB接続
// $db = new PDO("mysql:host=$dbServer;dbname=$dbName;charset=utf8","$dbUser","$dbPass");
// $db ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
// $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

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
            <!--ライブ名表示-->
            <?= h($row['live_name']) ?>
        </td>
        <td>
            <!--削除ボタン表示 POSTメソッドでlive_idを削除部分に渡す-->
            <form method="POST">
                <button type="submit" name="live_delete">削除</button>
                <input type="hidden" name="live_id" value="<?= $row['live_id'] ?>">
            </form>
        </td>
    </tr>

<?php
}
?>

            </tbody>
        </table>

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

    </body>
</html>