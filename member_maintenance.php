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
    } catch (PDOException $e) {
        echo 'エラー発生：' . h($e->getMessage());
    }

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>メンバーメンテナンス</title>
    </head>
    <body>
        <h1>メンバーメンテナンス</h1>

        <h2>メンバー一覧</h2>
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
        <td>
            <!--名前表示-->
            <?= h($row['member_name']) ?>
        </td>
        <td>
            <!--追加ボタン表示 POSTメソッドでmember_idを削除部分に渡す-->
            <form method="POST">
                <button type="submit" name="member_delete">削除</button>
                <input type="hidden" name="member_id" value="<?= $row['member_id'] ?>">
            </form>
        </td>
    </tr>

<?php
}
?>

            </tbody>
        </table>

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

    </body>
</html>