<?php

// //DB設定読み込み
// require_once __DIR__ . '/conf/database_conf.php';
require_once __DIR__ . '/conf/heroku_database_conf.php';

// //h()関数読み込み
require_once __DIR__ . '/lib/h.php';

//validation() 関数読み込み
require_once __DIR__ . '/lib/validation.php';

try {
    //DB接続
    $db = new PDO("mysql:host=$dbServer;dbname=$dbName;charset=utf8","$dbUser","$dbPass");
    $db ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


    //バンドメンバー登録するメンバーのmembeer_idを受け取っていれば
    if(isset($_POST['member_id'])){
        //band_idバリデーション
        validation($_POST['band_id'],'バンドID',4);
        //メンバーを追加するバンドのband_idを取得
        $band_id = $_POST['band_id'];
        //member_idバリデーション
        validation($_POST['member_id'],'メンバーID',7);
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
}catch (PDOException $e) {
    echo 'データベースエラー発生：' . h($e->getMessage());
}catch (Exception $e){
    echo 'エラー発生' . h($e->getMessage());
}finally{
    //出演バンド一覧からband_idを受け取っていれば
    if(isset($_POST['band_id'])){
        //メンバーを追加するバンドのband_idを取得
        $band_id = $_POST['band_id'];
    }
    //出演バンド一覧からlive_idを受け取っていたら
    if(isset($_POST['live_id'])){
        //live_idを取得
        $live_id = $_POST['live_id'];
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>バンドメンバー登録</title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
        <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
		<link rel="stylesheet" href="https://milligram.github.io/styles/main.css">
    </head>
    <body>
        <main class="wrapper">
            <section class="container">
<?php
try{
    //もしlive_idがPOST送信されてこのページに来たら
    if (isset($_POST['live_id'])) {
        //live_idバリデーション
        validation($_POST['live_id'],'ライブID',7);
        //live_idを取得
        $live_id = $_POST['live_id'];
        //band_idバリデーション
        validation($_POST['band_id'],'バンドID',4);
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
            echo "<h3>" . h($row['live_name']) . "</h3>";
            echo "<h2>" . h($row['band_name']) . "</h2>";
        }
    }
}catch (PDOException $e) {
    echo 'データベースエラー発生：' . h($e->getMessage());
}catch (Exception $e){
    echo 'エラー発生' . h($e->getMessage());
}
?>
                <h1>バンドメンバー登録</h1>
                <!--メンバー入力フォーム-->
                <form method="POST">
                    <fieldset>
                        <div class="colomn">
                            <label for="memberId">メンバーID</label>
                            <input type="text" name="member_id" maxlength="7" placeholder="(例)2019001">
                            <p>半角数字で入力してください</p>
                            <input type="hidden" name="live_id" value="<?= $live_id ?>">
                            <input type="hidden" name="band_id" value="<?= $band_id ?>">
                            <input type="submit" value="add">
                        </div>
                    </fieldset>
                </form>
                <form method="GET" action="band_maintenance.php">
                    <input type="hidden" name="live_id" value="<?= $live_id ?>">
                    <input type="submit" value="戻る">
                </form>
            </section>
        </main>
    </body>
</html>