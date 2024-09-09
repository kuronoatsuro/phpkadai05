<?php

// SESSIONスタート
session_start();

// 関数を呼び出す
require_once('funcs.php');

// ログインチェック
loginCheck();

//1. POSTデータ取得
$book_name = $_POST['book_name']; // 名前を取得
$book_url = $_POST['book_url']; // Eメールアドレスを取得
$book_comment = $_POST['book_comment']; // 内容を取得
$id = $_POST['id'];// これ大事！

//2. DB接続します
$pdo = db_conn();

//３．データ登録SQL作成
$stmt = $pdo->prepare('UPDATE gs_an_table SET book_name = :book_name, book_url = :book_url, book_comment = :book_comment, date = sysdate() WHERE id = :id;');

// バインド変数を設定
$stmt->bindValue(':book_name', $book_name, PDO::PARAM_STR);
$stmt->bindValue(':book_url', $book_url, PDO::PARAM_STR);
$stmt->bindValue(':book_comment', $book_comment, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);// 数値の場合 PDO::PARAM_INT

$status = $stmt->execute(); //実行

//４．データ登録処理後
if ($status === false) {
    sql_error($stmt);
} else {
    redirect('select.php');
}

?>