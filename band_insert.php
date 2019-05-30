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

    } catch (PDOException $e) {
        echo 'エラー発生：' . h($e->getMessage());
    }

    //テスト用
    // echo "live_id:".$live_id."<br>";
    // echo "band_id:".$band_id."<br>";
    // echo "member_id:".$member_id."<br>";
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>バンド登録</title>
    </head>
    <body>
        <h1>バンド登録</h1>

        <!--バンド入力フォーム-->
        <form method="POST">
            <p>バンドID</p>
            <input type="text" name="member_id" size="7" maxlength="7">
            <p>半角英数字4文字で入力してください</p>
            <p>入力例：B001→(このライブの登録1番目</p>
            <p>バンドIDが被ると登録出来ません</p>
            <p>前ページから他バンドのIDを確認してから入力してください</p>
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