<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>SoulMatesライブ出演バンド管理</title>
    </head>
    <body>
        <h1>SoulMatesライブ出演バンド管理アプリβ版</h1>
        <p>ver0.0.0</p>


        <h2>ライブ一覧</h2>
        <table>
            <thead><th>ライブ</th><th>出演バンド表示</th></thead>
            <tbody>

<?php

//DB設定読み込み
require_once __DIR__ . '/conf/database_conf.php';

//h()関数読み込み
require_once __DIR__ . '/lib/h.php';

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
            <?= htmlspecialchars($row['live_name']) ?>
        </td>
        <td>
            <form method="POST">
                <button type="submit" name="live_choice">選択</button>
                <input type="hidden" name="live_id" value="<?= $row['live_id'] ?>">
            </form>
        </td>
    </tr>

<?php
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

    </body>
</html>