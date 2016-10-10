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

	$stmt = $dbh->prepare($sql);
	$stmt -> execute($param);
}

$sql = 'SELECT * FROM `posts` ORDER BY `created` DESC';

// SELECT文の実行
$stmt = $dbh->prepare($sql);
$stmt -> execute();

//格納する変数の初期化
$posts = array();

//繰り返し分でデータの取得
while(1){
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($rec == false){
      //データを最後まで取得した印なので終了
      break;
    }
    //取得したデータを配列に格納しておく
    $posts[] = $rec;


}

	$dbh = null;
	

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

    <ul>
    <?php
      foreach ($posts as $post_each) {
        echo '<li>';

        echo 'nickname'.$post_each['nickname'];
        echo 'comment'.$post_each['comment'];
        echo 'created'.$post_each['created'];
        echo '</li>';
        echo '<hr>';
      }

    ?>

    </ul>

</body>
</html>