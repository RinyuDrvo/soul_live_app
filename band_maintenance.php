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

        // //削除ボタンが押されたら
        // if (isset($_POST['live_delete'])) {
        //     //削除するlive_idを取得
        //     $live_id = $_POST['live_id'];
        //     //SQL準備(live_idのレコードを削除)
        //     $sql = "DELETE FROM live WHERE live_id = :live_id";
        //     $prepare = $db -> prepare($sql);
        //     //live_idに挿入する変数と型を指定
        //     $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
        //     //クエリ実行
        //     $prepare -> execute();
        // }
    } catch (PDOException $e) {
        echo 'エラー発生：' . h($e->getMessage());
    }

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>バンドメンテナンス</title>
    </head>
    <body>
<?php
    //もしlive_idがPOST送信されてこのページに来たら
    if (isset($_POST['live_id'])) {
        //live_idを取得
        $live_id = $_POST['live_id'];
        //SQL準備
        $sql = 'SELECT * FROM live WHERE live_id = :live_id';
        $prepare = $db->prepare($sql);
        //バインド
        $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare->execute();
        //結果をstdClassオブジェクト配列として取得
        $result = $prepare->fetchAll(PDO::FETCH_OBJ);
        //出力
        echo h("ライブ名：{$result->live_name}");
    }
?>
        <h1>出演バンド一覧</h1>
        <table>
            <thead><th>出演順番</th><th>バンドID</th><th>バンド名</th><th>メンバー</th><th>スケジュール</th><th></th><th></th><th></th></thead>
            <tbody>

<?php

//SQL準備
$sql = 'SELECT * FROM band ORDER BY band_id';
$prepare = $db->prepare($sql);
//クエリ実行
$prepare->execute();

//ライブ名をひとつずつrowに設定
foreach ($prepare as $row) {
?>

    <tr>
        <td>
            <!--出演順番表示-->
            <?= h($row['performance_num']) ?>
        <td>
            <!--バンドID表示-->
            <?= h($row['band_id']) ?>
        </td>
        <td>
            <!--バンド名表示-->
            <?= h($row['band_name']) ?>
        </td>
        <td>
            <!--メンバー表示-->
            <p>ここにバンドメンバーを表示</p>
            <p>ここにバンドメンバーを表示</p>
            <p>ここにバンドメンバーを表示</p>
            <p>ここにバンドメンバーを表示</p>
        </td>
        <td>
            <!--スケジュール表示-->
            <? h($row['performance_time']) ?>
        </td>
        <td>
            <!--削除ボタン表示 POSTメソッドでband_idを削除部分に渡す-->
            <form method="POST">
                <button type="submit" name="band_delete">削除</button>
                <input type="hidden" name="band_id" value="<?= $row['band_id'] ?>">
            </form>
        </td>
        <td>
            <!--変更ボタン表示 POSTメソッドでband_idを変更画面に渡す-->
            <form method="POST" active="update_band.php">
                <a href="update_band.php">変更</a>
                <input type="hidden" name="band_id" value="<?= $row['band_id'] ?>">
            </form>
        </td>
        <td>
            <!--バンドメンバー追加ボタン表示 POSTメソッドでband_idを追加画面に渡す-->
            <form method="POST">
                <a href="insert_band_member.php">バンドメンバー</a>
                <input type="hidden" name="band_id" value="<?= $row['band_id'] ?>">
            </form>
        </td>
    </tr>

<?php
}
?>

            </tbody>
        </table>

        <p>
            <a href="band_insert.php">
                ライブ追加
            </a>
        </p>
        <p>
            <a href="index.php">
                戻る
            </a>
        </p>

    </body>
</html>