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

    //追加ボタンが押されたら
    if (isset($_POST['member_insert'])) {
        //member_nameバリデーション
        validation($_POST['member_name'],'member_name','');
        //追加するメンバーの名前を取得
        $member_name = $_POST['member_name'];
        //member_idバリデーション
        validation($_POST['member_id'],'member_id',7);
        //追加するメンバーのIDを取得
        $member_id = $_POST['member_id'];
        //SQL準備(新規メンバーIDと名前をmemberテーブルに追加)
        $sql = "INSERT INTO member (member_id,member_name) VALUES (:member_id,:member_name)";
        $prepare = $db -> prepare($sql);
        //member_idに挿入する変数と型を指定
        $prepare -> bindValue(':member_id',$member_id,PDO::PARAM_STR);
        //member_nameに挿入する変数と型を指定
        $prepare -> bindValue(':member_name',$member_name,PDO::PARAM_STR);
        //クエリ実行
        $prepare -> execute();

        echo '<p>追加完了</p>';
    }
} catch (PDOException $e) {
    echo 'データベースエラー発生：' . h($e->getMessage());
}catch (Exception $e){
    echo 'エラー発生' . h($e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>メンバー追加</title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
        <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
		<link rel="stylesheet" href="https://milligram.github.io/styles/main.css">
    </head>
    <body>
        <main class="wrapper">
            <section class="container">
                <h1>メンバー追加</h1>
                <div class="example">
                    <!--メンバー入力フォーム-->
                    <form method="POST">
                        <fieldset>
                            <div class="colomn">
                                <label for="memberName">名前</label>
                                <input type="text" name="member_name" maxlength="20" placeholder="名前">
                                <label for="memberId">メンバーID</label>
                                <input type="text" name="member_id" maxlength="7" placeholder="(例)2019001">
                                <p>半角数字7文字で入力してください</p>
                                <p>入力例：2019001→(2019年1番目の登録者)</p>
                                <p>メンバーIDが被ると登録できません</p>
                                <p>前のページIDを確認してから入力してください</p>
                                <input type="submit" name="member_insert" value="add">
                            </div>
                        </fieldset>
                    </form>
                </div>
                <p>
                    <a href="member_maintenance.php">
                        戻る
                    </a>
                </p>
            </section>
        </main>
    </body>
</html>