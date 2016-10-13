<?php
// dbconnect.phpを読み込む
require('dbconnect.php');
// -----------------------------
// 編集ボタンクリック時
$editname = '';
$editcomment = '';
$id = '';
if (!empty($_GET['action']) && $_GET['action'] == 'edit') {
  // 該当のデータを取得する
  $sql = 'SELECT * FROM `posts` WHERE `id` = ?';
  $data[] = $_GET['id'];
  // SQL実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  // データを取得
  $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  // 取得した値を格納
  $editname = $rec['nickname'];
  $editcomment = $rec['comment'];
  $id = $rec['id'];
}
// -----------------------------

echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';



// ファイルのアップロード
// $picture_path = '';
// if (!empty($_FILES)) {
//   $filename = $_FILES['picture_path']['name'];
//   $picture_path = 'pictures/'.date('YmdHis').$filename;
//   move_uploaded_file($_FILES['picture_path']['tmp_name'], $picture_path);
// }
// -----------------------------
// POST送信された時の処理
if (!empty($_POST)) {
  if (empty($_POST['id'])) {
    // データを登録する
    $sql = 'INSERT INTO `posts`(`nickname`, `comment`, `created`, `delete_flag`, `picture_path`) VALUES (?, ?, now(), 0, ?)';
    $data[] = $_POST['nickname'];
    $data[] = $_POST['comment'];
    $data[] = $picture_path;
  } else {
    // データを更新する
    $sql = 'UPDATE `posts` SET `nickname`=?,`comment`=?, `picture_path`=? WHERE `id` = ?';
    $data[] = $_POST['nickname'];
    $data[] = $_POST['comment'];
    $data[] = $picture_path;
    $data[] = $_POST['id'];
  }
  // SQL実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
}
// -----------------------------
// データの削除処理
if (!empty($_GET['action']) && $_GET['action'] == 'delete') {
  // $sql = 'DELETE FROM `posts` WHERE `id` = ?';
  // 論理削除のSQL
  $sql = 'UPDATE `posts` SET `delete_flag`=1 WHERE `id` = ?';
  $data[] = $_GET['id'];
  // SQL実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  // bbs.phpに画面を遷移する
  header('Location: bbs.php');
  exit();
}
// -----------------------------
// LIKEが押された時の処理
if (!empty($_GET['action']) && $_GET['action'] == 'like') {
  $sql = 'UPDATE `posts` SET `likes`=`likes`+1 WHERE `id` = ?';
  $data[] = $_GET['id'];
  // SQL実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  // bbs.phpに画面を遷移する
  header('Location: bbs.php');
  exit();
}
// -----------------------------
// データの表示
$sql = 'SELECT * FROM `posts` WHERE `delete_flag` = 0 ORDER BY `created` DESC';
// SQL実行
$stmt = $dbh->prepare($sql);
$stmt->execute();
// データ格納用変数
$data = array();
// データを取得
while (1) {
  $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($rec == false) {
    break;
  }
  // 1レコードずつデータを格納
  $data[] = $rec;
}
// データベースを切断
$dbh = null;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <!-- ナビゲーションバー -->
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#page-top"><span class="strong-title"><i class="fa fa-linux"></i> Oneline bbs</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <!-- Bootstrapのcontainer -->
  <div class="container">
    <!-- Bootstrapのrow -->
    <div class="row">

      <!-- 画面左側 -->
      <div class="col-md-4 content-margin-top">
        <!-- form部分 -->
        <form action="bbs.php" method="post" enctype="multipart/form-data">
          <!-- nickname -->
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="nickname" class="form-control" id="validate-text" placeholder="nickname" required value="<?php echo $editname; ?>">
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- comment -->
          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required><?php echo $editcomment; ?></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- 画像指定ボタン -->
          <div class="form-group">
            <input type="file" name="picture_path">
          </div>
          <!-- つぶやくボタン -->
          <?php if ($editname == ''): ?>
            <button type="submit" class="btn btn-primary col-xs-12" disabled>つぶやく</button>
          <?php else: ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" class="btn btn-primary col-xs-12" disabled>更新する</button>
          <?php endif; ?>
        </form>
      </div>

      <!-- 画面右側 -->
      <div class="col-md-8 content-margin-top">
        <div class="timeline-centered">
        <p>総つぶやき数：<?php echo count($data); ?>件</p>
        <?php foreach($data as $d): ?>
          <article class="timeline-entry">
              <div class="timeline-entry-inner">
                <a href="bbs.php?action=edit&id=<?php echo $d['id']; ?>">
                  <div class="timeline-icon bg-success">
                      <i class="entypo-feather"></i>
                      <i class="fa fa-cogs"></i>
                  </div>
                </a>
                  <div class="timeline-label">
                    <?php
                      // いったん日時型に変換する（String型からDatetime型へ変換）
                      $created = strtotime($d['created']);
                      // 書式の変換
                      $created = date('Y/m/d', $created);
                    ?>
                      <h2><a href="#"><?php echo $d['nickname']; ?></a> <span><?php echo $created; ?></span></h2>
                      <p><?php echo $d['comment']; ?></p>
                      <!-- 画像表示 -->
                      <?php if($d['picture_path'] != ''): ?>
                        <div>
                          <img src="<?php echo $d['picture_path']; ?>" alt="" width="200px" height="200px">
                        </div>
                      <?php endif; ?>
                      <!-- サムズアップボタンの設置 -->
                      <a href="bbs.php?action=like&id=<?php echo $d['id']; ?>" class="thumbs"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> LIKE <?php echo $d['likes']; ?></a>
                      <a href="bbs.php?action=delete&id=<?php echo $d['id']; ?>" onclick="return confirm('本当に削除しますか？');"><i class="fa fa-trash trash" aria-hidden="true"></i></a>
                  </div>
              </div>
          </article>
        <?php endforeach; ?>

          <article class="timeline-entry begin">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                      <i class="entypo-flight"></i> +
                  </div>
              </div>
          </article>
        </div>
      </div>

    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>
</body>
</html>
Contact GitHub API Training Shop Blog About
