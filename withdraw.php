<?php

require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   退会ページ');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログイン認証
require('auth.php');

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  画面処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//POSTされていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  //例外処理
  try{
    //DB接続
    $dbh = dbConnect();
    //SQL文作成
    $sql1 = 'UPDATE users SET delete_flg = 1 WHERE id = :user_id';
    $sql2 = 'UPDATE illustration SET delete_flg = 1 WHERE user_id = :user_id';
    $sql3 = 'UPDATE bookmark SET delete_flg = 1 WHERE user_id = :user_id';

    //データ流し込み
    $data = array(':user_id' => $_SESSION['user_id']);

    //SQL実行 UPDATEはそれぞれのテーブルで行わなければならないため３回
    $stmt1 = queryPost($dbh, $sql1, $data);
    $stmt2 = queryPost($dbh, $sql2, $data);
    $stmt3 = queryPost($dbh, $sql3, $data);

    //クエリ実行成功の場合()
    if($stmt1 && $stmt2){
      //セッション削除
      session_destroy();
      debug('セッション変数の中身：'.print_r($_SESSION,true));
      debug('トップページへ遷移します。');
      header("Location:index.php"); //トップページへ
    //クエリ実行失敗した場合
    }else{
      debug('クエリ失敗');
      $err_msg['common'] = MSG07;
    }
  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = '退会';
//head部分読み込み
require('head.php');
?>
<body>
  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>
  <!-- メインコンテンツ -->
  <div id="content">
    <section id="main">
      <div class="form-container form-quit">
        <h2>
          退会
        </h2>
        <div class="err_msg_area">
          <p></p>
        </div>
        <p>本当に退会しますか？</p>
        <form action="" class="form" method="post">
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="退会する" name="submit">
          </div>
        </form>
      </div>
      <a href="mypage.php" class="return">&lt; マイページへ戻る</a>
    </section>
  </div>

  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
