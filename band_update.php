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

    //出演バンド一覧からlive_idを受け取っていたら
    if(isset($_POST['live_id'])){
        //live_idを取得
        $live_id = $_POST['live_id'];
    }

    //出演バンド一覧からband_idを受け取っていたら
    if(isset($_POST['band_id'])){
        //band_idを取得
        $band_id = $_POST['band_id'];
    }

    //登録するバンドのband_nameを受け取っていれば
    if(isset($_POST['band_name'])){
        //追加するバンドのlive_idを取得
        $live_id = $_POST['live_id'];
        //追加するバンドのband_idを取得
        $band_id = $_POST['band_id'];
        //追加するバンドのband_nameを取得
        $band_name = $_POST['band_name'];
        //追加するバンドの出演順を取得
        $performance_num = $_POST['performance_num'];
        //追加するバンドの持ち時間を取得
        $performance_time = $_POST['performance_time'];
        //SQL準備(bandテーブルの対象レコードを更新)
        $sql = "UPDATE band SET
        band_name=:band_name,performance_num=:performance_num,performance_time=:performance_time
        WHERE live_id=:live_id AND band_id=:band_id";
        $prepare = $db -> prepare($sql);
        //live_idに挿入する変数と型を指定
        $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
        //band_idに挿入する変数と型を指定
        $prepare -> bindValue(':band_id',$band_id,PDO::PARAM_STR);
        //band_nameに挿入する変数と型を指定
        $prepare -> bindValue(':band_name',$band_name,PDO::PARAM_STR);
        //performance_timeに挿入する変数と型を指定
        $prepare -> bindValue(':performance_time',$performance_time,PDO::PARAM_STR);
        //performance_numに挿入する変数と型を指定
        $prepare -> bindValue(':performance_num',$performance_num,PDO::PARAM_INT);
        //クエリ実行
        $prepare -> execute();

        echo '<p>更新完了</p>';
    }
}catch (PDOException $e) {
    echo 'データベースエラー発生：' . h($e->getMessage());
}catch (Exception $e){
    echo 'エラー発生' . h($e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>バンド情報更新</title>
    </head>
    <body>
        <h1>バンド情報更新</h1>

        <!--バンド入力フォーム-->
        <form method="POST">
            <p>バンド名</p>
            <input type="text" name="band_name" size="30" maxlength="30">
            <p>出演順</p>
            <input type="text" name="performance_num" size="3">
            <p>持ち時間</p>
            <input type="text" name="performance_time" size="20" maxlength="20"><br>
            <input type="hidden" name="live_id" value="<?= $live_id ?>">
            <input type="hidden" name="band_id" value="<?= $band_id ?>">
            <input type="submit" value="更新">
        </form>

        <form method="GET" action="band_maintenance.php">
            <input type="hidden" name="live_id" value="<?= $live_id ?>">
            <input type="submit" value="戻る">
        </form>

    </body>
</html>