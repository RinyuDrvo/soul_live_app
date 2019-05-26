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
        if (isset($_POST['member_insert'])) {
            //追加するメンバーの名前を取得
            $member_name = $_POST['member_name'];
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
        echo 'エラー発生：' . h($e->getMessage());
    }

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>メンバー追加</title>
    </head>
    <body>
        <h1>メンバー追加</h1>

        <!--メンバー入力フォーム-->
        <form method="POST">
            <p>メンバー名</p>
            <input type="text" name="member_name" size="20" maxlength="20">
            <p>メンバーID</p>
            <input type="text" name="member_id" size="7" maxlength="7"><br>
            <p>入力例：2019001→(2019年1番目の登録者)</p>
            <p>※必ず7文字で登録して下さい</p>
            <p>IDが被るとエラーになり登録できません。</p>
            <p>前のページIDを確認してから入れてください。</p>
            <p>半角数字で入力してください</p>
            <button type="submit" name="member_insert">追加</button>
        </form>

        <p>
            <a href="member_maintenance.php">
                戻る
            </a>
        </p>

    </body>
</html>