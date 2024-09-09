<?php

session_start();
require_once('funcs.php');
loginCheck();

// id の取得とバリデーション
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('Invalid ID');
}
$id = $_GET['id'];

$pdo = db_conn();

// データ取得SQL作成
$stmt = $pdo->prepare('SELECT * FROM gs_an_table WHERE id=:id;');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

// データ表示
if ($status == false) {
    sql_error($stmt);
} else {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        exit('Data not found');
    }
};
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>データ更新</title>
    <link href="css/style.css" rel="stylesheet">

</head>

<body>

    <!-- Head[Start] -->
    <nav class="navbar">
        <a class="navbar-brand" href="index.php">データ登録</a>
        <form class="logout-form" action="logout.php" method="post" onsubmit="return confirm('本当にログアウトしますか？');">
            <button type="submit" class="logout-button">ログアウト</button>
        </form>
    </nav>
    <!-- Head[End] -->

    <!-- Main[Start] -->
    <div class="container">
        <h1>編集</h1>

        <form method="POST" action="update.php" enctype="multipart/form-data">
            <fieldset>
                <input type="hidden" name="id" value="<?= $id; ?>" />
                <div class="form-group">
                    <label for="book_name">書籍名</label>
                    <input type="text" id="book_name" name="book_name" value="<?= $row['book_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="book_url">書籍URL</label>
                    <input type="text" id="book_url" name="book_url" value="<?= $row['book_url']; ?>">
                </div>
                <div class="form-group">
                    <label for="book_comment">書籍コメント</label>
                    <textarea id="book_comment" name="book_comment" rows="4"><?= $row['book_comment']; ?></textarea>
                </div>
                <?php
                    if (!empty($row['image'])) {
                    echo '<img src="' . h($row['image']) . '" class="image-class">';
                    }
                ?>
                <input type="submit" value="更新">
            </fieldset>
        </form>
    </div>
    <!-- Main[End] -->

</body>

</html>
