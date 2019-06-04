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
        //live_idバリデーション
        validation($_POST['live_id'],'ライブID',7);
        //追加するバンドのlive_idを取得
        $live_id = $_POST['live_id'];
        //band_idバリデーション
        validation($_POST['band_id'],'バンドID',4);
        //追加するバンドのband_idを取得
        $band_id = $_POST['band_id'];
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
        <title>バンド情報更新</title>
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
                <h1>バンド情報更新</h1>
                <!--バンド入力フォーム-->
                <form method="POST">
                    <fieldset>
                        <div class="colomn">
                            <p>全ての項目を入力してください</p>
                            <label for="bandName">バンド名</label>
                            <input type="text" name="band_name" maxlength="30" placeholder="バンド名">
                            <label for="performanceNum">出演順</label>
                            <input type="text" name="performance_num" placeholder="半角数字で1~">
                            <label for="performanceTime">持ち時間</label>
                            <input type="text" name="performance_time" maxlength="20" placeholder="(例)13:20~13:40">
                            <input type="hidden" name="live_id" value="<?= $live_id ?>">
                            <input type="hidden" name="band_id" value="<?= $band_id ?>">
                            <input type="submit" value="update">
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