<?php

//2. DB接続します
require_once('funcs.php');
$pdo = db_conn();

//1. POSTデータ取得
$book_name = $_POST['book_name']; // 名前を取得
$book_url = $_POST['book_url']; // Eメールアドレスを取得
$book_comment = $_POST['book_comment']; // 内容を取得

// 画像アップロードの処理

$image_path = '';

// そもそもファイルデータがない場合は画像保存に関する一連の処理は不要なのでif文を使う 
if (isset($_FILES['image'])) {

	// imageの部分はinput type="file"のname属性に相当します。
	// 必要に応じて書き換えるべき場所です。
	$upload_file = $_FILES['image']['tmp_name'];
	
	//画像の拡張子を取得
	$extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
	
	// 画像名を取得。今回はuniqid()をつかって　保存時の時刻情報をファイル名とする
	$file_name = uniqid() . '.' . $extension;
	
	// フォルダ名を取得。今回は直書き。
	$dir_name = 'img/';
	
	// image_pathを確認
	$image_path = $dir_name . $file_name;	

    // move_uploaded_file()で、一時的に保管されているファイルをimage_pathに移動させる。
    if (!move_uploaded_file($upload_file, $image_path)) {
        exit('ファイルの移動に失敗しました。');
    }
}

//３．データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO gs_an_table(book_name, book_url, book_comment, image, date) VALUES(:book_name, :book_url, :book_comment, :image, NOW())");

// 数値の場合 PDO::PARAM_INT
// 文字の場合 PDO::PARAM_STR
$stmt->bindValue(':book_name', $book_name, PDO::PARAM_STR);
$stmt->bindValue(':book_url', $book_url, PDO::PARAM_STR);
$stmt->bindValue(':book_comment', $book_comment, PDO::PARAM_STR);
$stmt->bindValue(':image', $image_path, PDO::PARAM_STR);

$status = $stmt->execute(); //実行

//４．データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {
    redirect('index.php');
};

?>