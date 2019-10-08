
<?php

//関数等読みこみ
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   パスワード変更ページ');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログイン認証ファイル読み込み
require('auth.php');

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  画面処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//DBからユーザー情報を取得
$userData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：'.print_r($userData,true));

//POSTされていた場合
if(!empty($_POST)){
  debug('POST送信があります');
  debug('POSU情報：'.print_r($_POST,true));

  //変数にユーザー情報を代入
  $pass_old = $_POST['pass_old'];
  $pass_new = $_POST['pass_new'];
  $pass_new_re = $_POST['pass_new_re'];

  //未入力チェック
  validRequired($pass_old, 'pass_old');
  validRequired($pass_new, 'pass_new');
  validRequired($pass_new_re, 'pass_new_re');

  if(empty($err_msg)){
    debug('バリデーション未入力チェッククリア');

    //古いパスワードのチェック
    validPass($pass_old, 'pass_old');
    //新しいパスワードのチェック
    validPass($pass_new, 'pass_new');

    //古いパスワードとDBパスワードを照合(DBに入っているデータと同じであれば、半角英数字チェックや最大文字数チェックは行わなくても問題ない)
    if(!password_verify($pass_old, $userData['password'])){
      $err_msg['pass_old'] = MSG10;
    }

    //新しいパスワードと古いパスワードが同じかチェック
    //古いパスワードと新しいパスワードが同じ場合
    if($pass_old === $pass_new){
      $err_msg['pass_new'] = MSG11;
    }
    //パスワードとパスワード再入力があっているかチェック
    //(ログイン画面では最大、最小チェックもしていたが、パスワードの方でチェックしているので必要ない)
    validPassMatch($pass_new, $pass_new_re, 'pass_new_re');

    if(empty($err_msg)){
      debug('バリデーションチェッククリア');

      //例外処理
      try{
        //DB接続
        $dbh = dbConnect();
        //SQL文作成
        $sql = 'UPDATE users SET password = :pass WHERE id = :user_id';
        $data = array('user_id' => $_SESSION['user_id'], 'pass' => password_hash($pass_new, PASSWORD_DEFAULT));
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        //クエリ成功の場合
        if($stmt){
          //debug('クエリ成功');
          $_SESSION['msg_success'] = SUC01;

          //メールを送信
          $nickname = ($userData['nickname']) ? $userData['nickname'] : '名無し';
          $from = 'info@test.com';
          $to = $userData['email'];
          $subject = 'パスワード変更通知｜イラストサイト';

          //ヒアドキュメント
          //EOTはEndOfFileの略 ABCでもなんでもOK。先頭の<<<の後の文字列と合わせること。最後のEOTの前後に空白など何も入れてないけない。
          //EOT内の半角空白も全てそのまま半角空白として扱われてしまうので、インデントはしないこと。
          $comment = <<<EOT
{$username} さん
パスワードが変更されました。

//////////////////////////////////////
イラストサイトカスタマーセンター
URL: test.com
E-mail: test@test.com
//////////////////////////////////////
EOT;
          sendMail($from, $to, $subject, $comment);

          header("Location:mypage.php");//マイページへ
//        }else{
//          debug('クエリに失敗しました。');
//          $err_msg['common'] = MSG07;
//        }

      }
    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
        $err_msg['common'] = MSG07;
      }
    }
  }
}
?>
<?php
$siteTitle = 'パスワード変更';
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
      <div class="form-container">
        <h2>パスワード変更</h2>
        <form action="" class="form" method="post">
          <div class="err_msg_area">
            <p>
              <?php echo getErrMsg('common'); ?>
            </p>
          </div>
          <label for="" class="<?php if(!empty($err_msg['pass_old'])) echo 'err'; ?>">
            古いパスワード
            <span class="err_msg_area"><?php echo getErrMsg('pass_old'); ?></span>
            <input type="password" name="pass_old">
          </label>
          <label for="" class="<?php if(!empty($err_msg['pass_new'])) echo 'err'; ?>">
            新しいパスワード
            <span>※英数字6文字以上</span>
            <span class="err_msg_area"><?php echo getErrMsg('pass_new'); ?></span>
            <input type="password" name="pass_new">
          </label>
          <label for="" class="<?php if(!empty($err_msg['pass_new_re'])) echo 'err'; ?>">
            新しいパスワード(再入力)
            <span class="err_msg_area"><?php echo getErrMsg('pass_new_re'); ?></span>
            <input type="password" name="pass_new_re">
          </label>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="変更する">
          </div>
        </form>
      </div>
      <a href="mypage.php" class="return">&lt マイページへ戻る</a>
    </section>
  </div>

  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
