<?php

//DB設定読み込み
// require_once __DIR__ . '/conf/database_conf.php';
require_once __DIR__ . '/conf/heroku_database_conf.php';

//h()関数読み込み
require_once __DIR__ . '/lib/h.php';

//validation() 関数読み込み
require_once __DIR__ . '/lib/validation.php';

try {
    //DB接続
    $db = new PDO("mysql:host=$dbServer;dbname=$dbName;charset=utf8","$dbUser","$dbPass");
    $db ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //追加ボタンが押されたら
    if (isset($_POST['live_insert'])) {
        //live_nameバリデーション
        validation($_POST['live_name'],'ライブ名','');
        //追加するライブ名を取得
        $live_name = $_POST['live_name'];
        //live_idバリデーション
        validation($_POST['live_id'],'ライブID',7);
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
    echo 'データベースエラー発生:' . h($e->getMessage());
}
catch (Exception $e){
    echo 'その他エラー発生:' . h($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ライブ追加</title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
        <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
		<link rel="stylesheet" href="https://milligram.github.io/styles/main.css">
    </head>
    <body>
        <main class="wrapper">
            <section class="container">
                <h1 class="titles">ライブ追加</h1>
                <div class="example">
                    <!--ライブ入力フォーム-->
                    <form method="POST">
                        <fieldset>
                            <div class="colomn">
                                <label for="liveName">ライブ名</label>
                                <input type="text" name="live_name" maxlength="30" id="liveName" placeholder="ライブ名">
                                <label for="liveID">ライブID</label>
                                <input type="text" name="live_id" maxlength="7" id="liveId" placeholder="(例)201901A"><br>
                                <p>半角英数字7文字で入力してください</p>
                                <p>入力例：201901A→(2019年1回目のライブのA日程)</p>
                                <p>ライブIDが被ると登録できません</p>
                                <p>前のページで他ライブのIDを確認してから入力してください</p>
                                <input type="submit" name="live_insert" value="add">
                            </div>
                        </fieldset>
                    </form>
                </div>
                <p>
                    <a href="live_maintenance.php">
                        戻る
                    </a>
                </p>
            </section>

        </main>
    </body>
</html>