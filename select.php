<?php
// SESSIONスタート
session_start();

// 関数を呼び出す
require_once('funcs.php');

// ログインチェック
loginCheck();

// 以下ログインユーザーのみ

//２．データ登録SQL作成
$pdo = db_conn();
$stmt = $pdo->prepare('SELECT * FROM gs_an_table');
$status = $stmt->execute();

//３．データ表示
if ($status == false) {
    sql_error($stmt);
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ブックマーク表示</title>
    <link href="css/style.css" rel="stylesheet">

</head>

<body id="main">
    <nav class="navbar">
        <a class="navbar-brand" href="index.php">データ登録</a>
        <form class="logout-form" action="logout.php" method="post" onsubmit="return confirm('本当にログアウトしますか？');">
            <button type="submit" class="logout-button">ログアウト</button>
        </form>
    </nav>

    <div class="container">
        <h1>結果表示</h1>
            <div class="book-list">
                <?php while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                    <p>
                        <a href="detail.php?id=<?= htmlspecialchars($result['id'], ENT_QUOTES, 'UTF-8')?>">
                            <?= htmlspecialchars($result['date'], ENT_QUOTES, 'UTF-8') ?> :
                            <?= htmlspecialchars($result['book_name'], ENT_QUOTES, 'UTF-8') ?> - 
                            <?= htmlspecialchars($result['book_comment'], ENT_QUOTES, 'UTF-8') ?> 
                        </a>
                        <div>
                            <img src="<?= h($r['image']) ?>" alt="">
                        </div>
                        <form method="POST" action="delete.php">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($result['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="submit" value="削除">
                        </form>
                    </p>
                <?php endwhile; ?>
            </div>
    </div>
</body>

</html>