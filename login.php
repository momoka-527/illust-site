<?php

//関数等読みこみ
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   ログインページ');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログイン認証ファイル読み込み
require('auth.php');
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  ログイン画面処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//POST送信されていた場合
if(!empty($_POST)){
  debug('ログインページ/POST送信有り');

  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_save = (!empty($_POST['pass_save'])) ? true : false;

  //バリデーションチェック
  //Email型式チェック
  validEmail($email, 'email');
  //最大文字数チェック
  validMaxLen($email, 'email');

  //パスワード最大文字数チェック
  validMaxLen($pass, 'pass');
  //パスワード最小文字数チェック
  validMinLen($pass, 'pass');
  //パスワード半角英数字チェック
  validHalf($pass, 'pass');

  //未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');

  //バリデーションチェッククリア
  if(empty($err_msg)){
    debug('バリデーションチェッククリア');
    //例外処理
    try{
      //DB接続
      $dbh = dbConnect();
      //SQL文作成
      $sql = 'SELECT password,id FROM users WHERE email = :email AND delete_flg = 0';
      $data = array(':email' => $email);
      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      //クエリ結果の値を取得
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      debug('クエリ結果の中身($resultの中身)：'.print_r($result,true));

      //パスワード照合
      //パスワードがマッチした場合
      if(!empty($result) && password_verify($pass, $result['password'])){ //password_verify — パスワードがハッシュにマッチするかどうかを調べる
        debug('パスワード照合の結果、マッチしました。');

        //ログイン有効期限(デフォルトを一時間とする)
        $sesLimit = 60*60;
        //最終ログイン日時を現在日時に更新する
        $_SESSION['login_date'] = time(); //UNIXタイムスタンプ使用

        //ログイン保持にチェックがある場合
        if($pass_save){
          debug('ログイン保持にチェック有り');

          //ログイン有効期限を30日にセットする
          $_SESSION['login_limit'] = $sesLimit * 24 * 30;

        //ログイン保持にチェックがない場合
        }else{
          debug('ログイン保持にチェック無し');

          //次回からログイン保持しないので、ログイン有効期限を一時間後にセットする
          $_SESSION['login_limit'] = $sesLimit;
        }

        //ユーザーIDを格納
        $_SESSION['user_id'] = $result['id'];

        debug('セッション変数の中身:'.print_r($_SESSION,true));
        debug('マイページへ遷移します');
        header("Location:mypage.php");//マイページへ
      //パスワードがマッチしなかった場合
    }else{
      debug('パスワードがマッチしませんでした。');
      $err_msg['common'] = MSG09;
    }
    }catch(Exception $e){
      error_log('エラーが発生しました。：'.$e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
 ?>
<?php
$siteTitle = 'ログイン';
//head部分読み込み
require('head.php');
?>
<body>
  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>

  <p id="js-show-msg" style="display:none;" class="msg-slide">
    <?php echo getSessionFlash('msg_success'); ?>
  </p>

    <div id="content">
      <section id="main">
        <div class="form-container">
          <h2>ログイン</h2>
          <form action="" class="form" method="POST">
            <div class="err_msg_area">
              <p>
                <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
              </p>
            </div>
            <label for="" class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
              メールアドレス
              <span class="err_msg_area"><?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?></span>
              <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
            </label>
            <label for="" class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
              パスワード
              <span class="err_msg_area"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?></span>
              <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
            </label>
            <label for="">
              <input type="checkbox" name="pass_save">次回ログインを省略する
            </label>
            <div class="btn-container">
              <input type="submit" class="btn btn-mig" value="ログイン">
            </div>
            パスワードを忘れた方は<a href="passRemindSend.php">コチラ</a>
          </form>
        </div>
      </section>
    </div>
    <!-- フッター -->
    <?php
    require('footer.php');
    ?>
