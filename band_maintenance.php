<?php

// //DB設定読み込み
// require_once __DIR__ . '/conf/database_conf.php';
require_once __DIR__ . '/conf/heroku_database_conf.php';

// //h()関数読み込み
require_once __DIR__ . '/lib/h.php';

//例外処理
try {
    //DB接続
    $db = new PDO("mysql:host=$dbServer;dbname=$dbName;charset=utf8","$dbUser","$dbPass");
    $db ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //バンド削除ボタンが押されたら
    if (isset($_POST['band_delete'])) {
        //削除するband_idを取得
        $band_id = $_POST['band_id'];
        //バンドテーブルのband_idのレコードを削除
        //SQL準備
        $sql = "DELETE FROM band WHERE band_id = :band_id";
        $prepare = $db -> prepare($sql);
        //band_idバインド
        $prepare -> bindValue(':band_id',$band_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare -> execute();

        //フォーメーションテーブルのband_idのレコードを削除
        //SQL準備
        $sql = "DELETE FROM formation WHERE band_id = :band_id";
        $prepare = $db -> prepare($sql);
        //live_idに挿入する変数と型を指定
        $prepare -> bindValue(':band_id',$band_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare -> execute();

        //結果表示
        echo "<p>バンド消去成功</p>";
    }

    //メンバー削除ボタンが押されたら
    if(isset($_POST['member_delete'])){
        //削除するband_idを取得
        $band_id = $_POST['band_id'];
        //formationテーブルのband_idのレコードを削除
        //SQL準備
        $sql = "DELETE FROM formation WHERE band_id = :band_id";
        $prepare = $db -> prepare($sql);
        //band_idバインド
        $prepare -> bindValue(':band_id',$band_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare -> execute();

        //結果表示
        echo "<p>メンバー消去成功</p>";
    }

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>バンドメンテナンス</title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
        <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
		<link rel="stylesheet" href="https://milligram.github.io/styles/main.css">
    </head>
    <body>
        <main class="wrapper">
            <section class="container">
<?php
    //もしlive_idがGET送信されてこのページに来たら
    if (isset($_GET['live_id'])) {
        //live_idを取得
        $live_id = $_GET['live_id'];
        //SQL準備
        $sql = 'SELECT * FROM live WHERE live_id = :live_id';
        $prepare = $db->prepare($sql);
        //バインド
        $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare->execute();
        //出力
        foreach ($prepare as $row) {
            echo "<h2>" . h($row['live_name']) . "</h2>";
        }
    }
?>
                <h1>出演バンド一覧</h1>
                <div class="example">
                    <table>
                        <thead>
                            <th>出演順番</th>
                            <th>バンドID</th>
                            <th>バンド名</th>
                            <th>メンバー</th>
                            <th>持ち時間</th>
                            <th>バンド削除</th>
                            <th>バンド情報更新</th>
                            <th>メンバー登録</th>
                            <th>メンバー削除</th>
                        </thead>
                        <tbody>

<?php

    //もしlive_idがPOST送信されてこのページに来たら
    if (isset($_GET['live_id'])) {
        //SQL準備
        $sql = 'SELECT * from band
            WHERE live_id=:live_id
            ORDER BY performance_num';
        $prepare = $db->prepare($sql);
        //バインド
        $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
        //クエリ実行
        $prepare->execute();

        //ライブ名をひとつずつrowに設定
        foreach ($prepare as $row) {
            //メンバーSELECT用にバンドIDを変数に設定
            $band_id = $row['band_id'];
?>

                            <tr>
                                <td>
                                    <!--出演順番表示-->
                                    <?= h($row['performance_num']) ?>
                                </td>
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
<?php
            //SQL準備
            $sql = "SELECT
            member.member_name
            FROM member
                INNER JOIN formation
                    ON member.member_id=formation.member_id
                INNER JOIN band
                    ON formation.band_id=band.band_id
            WHERE band.live_id=:live_id AND band.band_id=:band_id";
            $prepare = $db->prepare($sql);
            //ライブIDでバインド
            $prepare -> bindValue(':live_id',$live_id,PDO::PARAM_STR);
            //バンドIDでバインド
            $prepare -> bindValue(':band_id',$band_id,PDO::PARAM_STR);
            //クエリ実行
            $prepare->execute();
            //メンバー名をひとつずつrowに設定
            foreach ($prepare as $row_n) {
                echo h($row_n['member_name']). "/";
            }
?>
                                </td>
                                <td>
                                    <!--スケジュール表示-->
                                    <?= h($row['performance_time']) ?>
                                </td>
                                <td>
                                    <!--削除ボタン表示 POSTメソッドでband_idを削除部分に渡す-->
                                    <form method="POST">
                                        <input type="hidden" name="band_id" value="<?= $row['band_id'] ?>">
                                        <input type="submit" name="band_delete" value="delete">
                                    </form>
                                </td>
                                <td>
                                    <!--バンド情報更新ボタン表示 POSTメソッドでband_idを変更画面に渡す-->
                                    <form method="POST" action="band_update.php">
                                        <input type="hidden" name="band_id" value="<?= $row['band_id'] ?>">
                                        <input type="hidden" name="live_id" value="<?= $live_id ?>">
                                        <input type="submit" value="update">
                                    </form>
                                </td>
                                <td>
                                    <!--バンドメンバー登録ボタン表示 POSTメソッドでband_idを追加画面に渡す-->
                                    <form method="POST" action="formation.php">
                                        <input type="hidden" name="band_id" value="<?= $row['band_id'] ?>">
                                        <input type="hidden" name="live_id" value="<?= $live_id ?>">
                                        <input type="submit" value="entry">
                                    </form>
                                </td>
                                <td>
                                    <!-- バンドメンバー削除（一括） -->
                                    <form method="POST">
                                        <input type="hidden" name="band_id" value="<?= $row['band_id'] ?>">
                                        <input type="submit" name="member_delete" value="member">
                                    </form>
                                </td>
                            </tr>

<?php
        }
    }
}catch (PDOException $e) {
    echo 'データベースエラー発生：' . h($e->getMessage());
}catch (Exception $e){
    echo 'エラー発生' . h($e->getMessage());
}
?>

                        </tbody>
                    </table>
                </div>

                <p>バンド追加</p>
                <p>
                    <!-- バンド追加画面へ(live_idを渡す)-->
                    <form method="POST" action="band_insert.php">
                        <input type="hidden" name="live_id" value="<?= $live_id ?>">
                        <input type="submit" value="add">
                    </form>
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