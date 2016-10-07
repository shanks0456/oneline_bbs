<?php
  // ここにDBに登録する処理を記述する

$dsn = 'mysql:dbname=oneline_bbs;host=localhost';
$user = 'root';
$password='';
$dbh = new PDO($dsn, $user, $password);
$dbh->query('SET NAMES utf8');



if (isset($_POST) && !empty($_POST)){

	$sql = 'INSERT INTO `posts`(`nickname`, `comment`, `created`) VALUES (?, ?, now())';

	$param[] = $_POST['nickname'];
	$param[] = $_POST['comment'];

	var_dump ($param);

	$stmt = $dbh->prepare($sql);
$stmt -> execute($param);
	
	$dbh = null;
	
}

// $sql = 'SELECT * FROM `posts` ORDER BY `created` DESC';

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>
</head>
<body>
    <form method="post" action="">
      <p><input type="text" name="nickname" placeholder="nickname"></p>
      <p><textarea type="text" name="comment" placeholder="comment"></textarea></p>
      <p><button type="submit" >つぶやく</button></p>
    </form>
    <!-- ここにニックネーム、つぶやいた内容、日付を表示する -->

</body>
</html>