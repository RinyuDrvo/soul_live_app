<?php

    // //DB設定読み込み
    require_once __DIR__ . '/conf/database_conf.php';

    // //h()関数読み込み
    require_once __DIR__ . '/lib/h.php';


    try {
        //DB接続
        $db = new PDO("mysql:host=$dbServer;dbname=$dbName;charset=utf8","$dbUser","$dbPass");
        $db ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        //追加ボタンが押されたら
        if (isset($_POST['live_insert'])) {
            //追加するライブ名を取得
            $live_name = $_POST['live_name'];
            //追加するライブIDを取得
            $live_id = $_POST['live_id'];
            //SQL準備(新規ライブIDとライブ名をliveテーブルに追加)
            $sql = "INSERT INTO live (live_id,live_name) VALUES (:live_id,:live_name)";
            $prepare = $db -> prepare($sql);
            //live_idに挿入する変数と型を指定
            $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
            //live_nameに挿入する変数と型を指定
            $prepare -> bindValue(':live_name',$live_name,PDO::PARAM_STR);
            //クエリ実行
            $prepare -> execute();

            echo '<p>追加完了</p>';
        }
    } catch (PDOException $e) {
        echo 'エラー発生：' . h($e->getMessage());
    }

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ライブ追加</title>
    </head>
    <body>
        <h1>ライブ追加</h1>

        <!--ライブ入力フォーム-->
        <form method="POST">
            <p>ライブ名</p>
            <input type="text" name="live_name" size="30" maxlength="30">
            <p>ライブID</p>
            <input type="text" name="live_id" size="7" maxlength="7"><br>
            <button type="submit" name="live_insert">追加</button>
        </form>

        <p>
            <a href="live_maintenance.php">
                戻る
            </a>
        </p>

    </body>
</html>