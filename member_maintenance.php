<?php

// //DB設定読み込み
// require_once __DIR__ . '/conf/database_conf.php';
require_once __DIR__ . '/conf/heroku_database_conf.php';

// //h()関数読み込み
require_once __DIR__ . '/lib/h.php';

try {

    //DB接続
    $db = new PDO("mysql:host=$dbServer;dbname=$dbName;charset=utf8","$dbUser","$dbPass");
    $db ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //削除ボタンが押されたら
    if (isset($_POST['member_delete'])) {
        //削除するmember_idを取得
        $member_id = $_POST['member_id'];
        //SQL準備(member_idのレコードを削除)
        $sql = "DELETE FROM member WHERE member_id = :member_id";
        $prepare = $db -> prepare($sql);
        //member_idに挿入する変数と型を指定
        $prepare -> bindValue(':member_id',$member_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare -> execute();

        //結果表示
        echo "<p>消去成功</p>";
    }

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>メンバーメンテナンス</title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
        <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
		<link rel="stylesheet" href="https://milligram.github.io/styles/main.css">
    </head>
    <body>
        <main class="wrapper">
            <section class="container">
                <h2>メンバーメンテナンス</h2>

                <h1>メンバー一覧</h1>
                <div class="example">
                    <table>
                        <thead><th>メンバーID</th><th>名前</th><th></th></thead>
                        <tbody>

<?php

    //SQL準備
    $sql = 'SELECT * FROM member ORDER BY member_id';
    $prepare = $db->prepare($sql);
    //クエリ実行
    $prepare->execute();

    //メンバー名をひとつずつrowに設定
    foreach ($prepare as $row) {
?>

                            <tr>
                                <td>
                                    <!--メンバーID表示-->
                                    <?= h($row['member_id']) ?>
                                </td>
                                <td>
                                    <!--名前表示-->
                                    <?= h($row['member_name']) ?>
                                </td>
                                <td>
                                    <!--追加ボタン表示 POSTメソッドでmember_idを削除部分に渡す-->
                                    <form method="POST">
                                        <input type="submit" name="member_delete" value="delete">
                                        <input type="hidden" name="member_id" value="<?= h($row['member_id']) ?>">
                                    </form>
                                </td>
                            </tr>

<?php
    }
} catch (PDOException $e) {
    echo 'データベースエラー発生：' . h($e->getMessage());
}catch (Exception $e){
    echo 'エラー発生' . h($e->getMessage());
}
?>

                        </tbody>
                    </table>
                </div>
                <p>
                    <a href="member_insert.php">
                        メンバー追加
                    </a>
                </p>
                <p>
                    <a href="index.php">
                        戻る
                    </a>
                </p>
            </section>
        </main>
    </body>
</html>