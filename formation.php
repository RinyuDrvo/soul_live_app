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

    // if (isset($_POST['band_id']) && isset($_POST['member_id'])) {

    //出演バンド一覧からband_idを受け取っていれば
    if(isset($_POST['band_id'])){
        //メンバーを追加するバンドのband_idを取得
        $band_id = $_POST['band_id'];
    }

    //バンドメンバー登録するメンバーのmembeer_idを受け取っていれば
    if(isset($_POST['member_id'])){
        //メンバーを追加するバンドのband_idを取得
        $band_id = $_POST['band_id'];
        //追加するメンバーのmember_idを取得
        $member_id = $_POST['member_id'];
        //SQL準備(formationテーブルに加入するband_idと加入者のmember_idを追加)
        $sql = "INSERT INTO formation (band_id,member_id) VALUES (:band_id,:member_id)";
        $prepare = $db -> prepare($sql);
        //band_idに挿入する変数と型を指定
        $prepare -> bindValue(':band_id',$band_id,PDO::PARAM_STR);
        //member_idに挿入する変数と型を指定
        $prepare -> bindValue(':member_id',$member_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare -> execute();

        echo '<p>追加完了</p>';
    }

    //出演バンド一覧からlive_idを受け取っていたら
    if(isset($_POST['live_id'])){
        //live_idを取得
        $live_id = $_POST['live_id'];
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>バンドメンバー登録</title>
    </head>
    <body>
<?php
    //もしlive_idがPOST送信されてこのページに来たら
    if (isset($_POST['live_id'])) {
        //live_idを取得
        $live_id = $_POST['live_id'];
        //band_idを取得
        $band_id = $_POST['band_id'];
        //SQL準備
        $sql = 'SELECT band_name,live_name FROM live
            INNER JOIN band ON live.live_id=band.live_id
        WHERE live.live_id = :live_id AND band.band_id=:band_id';
        $prepare = $db->prepare($sql);
        //live_idバインド
        $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
        //band_idバインド
        $prepare -> bindValue(':band_id',$band_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare->execute();
        //出力
        foreach ($prepare as $row) {
            echo "<h1>" . h($row['live_name']) . "</h1>";
            echo "<h2>" . h($row['band_name']) . "</h2>";
        }
    }
}catch (PDOException $e) {
    echo 'データベースエラー発生：' . h($e->getMessage());
}catch (Exception $e){
    echo 'エラー発生' . h($e->getMessage());
}
?>
        <h3>バンドメンバー登録</h3>

        <!--メンバー入力フォーム-->
        <form method="POST">
            <p>メンバーID</p>
            <input type="text" name="member_id" size="7" maxlength="7">
            <p>半角数字で入力してください</p>
            <input type="hidden" name="live_id" value="<?= $live_id ?>">
            <input type="hidden" name="band_id" value="<?= $band_id ?>">
            <input type="submit" value="登録">
        </form>

        <form method="GET" action="band_maintenance.php">
            <input type="hidden" name="live_id" value="<?= $live_id ?>">
            <input type="submit" value="戻る">
        </form>

    </body>
</html>