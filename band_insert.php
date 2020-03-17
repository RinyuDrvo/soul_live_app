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

    //登録するバンドのband_idを受け取っていれば
    if(isset($_POST['band_id'])){
        //live_idバリデーション
        validation($_POST['live_id'],'ライブID',7);
        //追加するバンドのlive_idを取得
        $live_id = $_POST['live_id'];
        //band_nameバリデーション
        validation($_POST['band_name'],'バンド名','');
        //追加するバンドのband_nameを取得
        $band_name = $_POST['band_name'];
        //performance_numバリデーション
        validation($_POST['performance_num'],'出演順','');
        //追加するバンドの出演順を取得
        $performance_num = $_POST['performance_num'];
        //performance_timeバリデーション
        validation($_POST['performance_time'],'持ち時間','');
        //追加するバンドの持ち時間を取得
        $performance_time = $_POST['performance_time'];
        //band_idバリデーション
        validation($_POST['band_id'],'バンドID',4);
        //追加するバンドのband_idを取得
        $band_id = $_POST['band_id'];
        //SQL準備(bandテーブルに各項目を挿入)
        $sql = "INSERT INTO band
        (live_id,band_id,band_name,performance_num,performance_time)
        VALUES (:live_id,:band_id,:band_name,:performance_num,:performance_time)";
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

        echo '<p>追加完了</p>';
    }

    //出演バンド一覧からlive_idを受け取っていたら
    if(isset($_POST['live_id'])){
        //live_idを取得
        $live_id = $_POST['live_id'];
    }
}catch (PDOException $e) {
    echo 'データベースエラー発生：' . h($e->getMessage());
}catch (Exception $e){
    echo 'エラー発生：' . h($e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>バンド登録</title>
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
        //live_ideバリデーション
        validation($_POST['live_id'],'ライブID',7);
        //live_idを取得
        $live_id = $_POST['live_id'];
        //SQL準備
        $sql = 'SELECT * FROM live WHERE live_id = :live_id';
        $prepare = $db->prepare($sql);
        //バインド
        $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare->execute();
        //出力
        foreach ($prepare as $row) {
            echo "<h1>" . h($row['live_name']) . "</h1>";
        }
    }
}catch (PDOException $e) {
    echo 'データベースエラー発生：' . h($e->getMessage());
}catch (Exception $e){
    echo 'エラー発生：' . h($e->getMessage());
}
?>
                <h2>バンド登録</h2>
                <!--バンド入力フォーム-->
                <form method="POST">
                    <fieldset>
                        <label for="nameField">バンド名</label>
                        <input type="text" name="band_name"  maxlength="30" placeholder="バンド名">
                        <label for="performanceNumField">出演順</label>
                        <input type="text" name="performance_num" placeholder="半角数字で1~">
                        <label for="performanceTimeField">持ち時間</label>
                        <input type="text" name="performance_time" maxlength="20" placeholder="(例)13:10~13:30">
                        <label for="bandIdField">バンドID</label>
                        <input type="text" name="band_id" maxlength="4" placeholder="(例)B001">
                        <p>半角英数字4文字で入力してください</p>
                        <p>入力例：B001→(このライブの登録1番目</p>
                        <p>バンドIDが被ると登録出来ません</p>
                        <p>前ページから他バンドのIDを確認してから入力してください</p>
                        <input type="hidden" name="live_id" value="<?= $live_id ?>">
                        <input type="submit" value="add">
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