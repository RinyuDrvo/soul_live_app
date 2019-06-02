<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>SoulMatesライブ出演バンド管理</title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
        <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
		<link rel="stylesheet" href="https://milligram.github.io/styles/main.css">
    </head>
    <body>
        <main class="wrapper">
            <section class="container" id="live-list">
                <h1 class="title">SoulMatesライブ出演バンド管理アプリβ版</h1>
                <p>ver0.0.0</p>


                <h2>ライブ一覧</h2>
                <table>
                    <thead>
                        <th>ライブ</th>
                        <th>出演バンド表示</th>
                    </thead>
                    <tbody>

<?php

//DB設定読み込み
require_once __DIR__ . '/conf/database_conf.php';

//h()関数読み込み
require_once __DIR__ . '/lib/h.php';

//例外処理
try{

    //DB接続
    $db = new PDO("mysql:host=$dbServer;dbname=$dbName;charset=utf8","$dbUser","$dbPass");
    $db ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //SQL準備
    $sql = 'SELECT * FROM live ORDER BY live_id';
    $prepare = $db->prepare($sql);
    //クエリ実行
    $prepare->execute();

    //ライブ名をひとつずつrowに設定
    foreach ($prepare as $row) {
?>

            <tr>
                <td>
                    <?= h($row['live_name']) ?>
                </td>
                <td>
                    <form method="GET" action="band_maintenance.php">
                        <input type="hidden" name="live_id" value="<?= $row['live_id'] ?>">
                        <input type="submit" value="choice">
                    </form>
                </td>
            </tr>

<?php
    }
}catch (PDOException $e) {
    echo 'データベースエラー発生：' . h($e->getMessage());
}catch (Exception $e){
    echo 'エラー発生' . h($e->getMessage());
}
?>

                    </tbody>
                </table>

                <p>
                    <a href="live_maintenance.php">
                        ライブメンテナンス
                    </a>
                </p>
                <p>
                    <a href="member_maintenance.php">
                        メンバーメンテナンス
                    </a>
                </p>
            </section>
        </main>
        <script src="https://milligram.github.io/scripts/main.js"></script>
    </body>
</html>