<?php
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   パスワード再発行認証キー入力ページ');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログインできない人が使う画面のため、ログイン認証はなし

//Sessionに認証キーがあるか確認、なければリダイレクト
if(empty($_SESSION['auth_key'])){
  header("Location:passRemindSend.php"); //認証キー送信ページへ
}

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  画面処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//POSTされていた場合
if(!empty($_POST)){
  debug('POST送信あり');
  debug('POSTの中身：'.print_r($_POST, true));

  //変数に認証キーを代入
  $auth_key = $_POST['token'];

  //未入力チェック
  validRequired($auth_key, 'token');

  if(empty($err_msg)){
    debug('未入力チェッククリア');

    //固定長チェック
    validLength($auth_key, 'token');
    //半角チェック
    validHalf($auth_key, 'token');

    if(empty($err_msg)){
      debug('バリデーションチェッククリア');

      if($auth_key !== $_SESSION['auth_key']){
        $err_msg['common'] = MSG13;
      }
      if(time() > $_SESSION['auth_key_limit']){
        $err_msg['common'] = MSG14;
      }

      if(empty($err_msg)){
        debug('認証OK');

        $pass = makeRandKey(); //パスワード生成

        //例外処理
        try{
          //DBへ接続
          $dbh = dbConnect();
          //SQL文作成
          $sql = 'UPDATE users SET password = :pass WHERE email = :email AND delete_flg = 0';
          $data = array(':email' => $_SESSION['auth_email'], ':pass' => password_hash($pass, PASSWORD_DEFAULT));
          //クエリ実行
          $stmt = queryPost($dbh, $sql, $data);

          //クエリ成功の場合
          if($stmt){
            debug('クエリ成功');

            //メールを送信
            $from = 'test@test.com';
            $to = $_SESSION['auth_email'];
            $subject = '【パスワード再発行完了】|イラストサイト';
            //EOTはEndOfFileの略。ABCでもなんでもOK.先頭の<<<の後の文字列と合わせること。EOTの前後に空白等何も入れてはいけない。
            //EOT内の半角空白も全てそのまま半角空白として扱われるので、インデントはしないこと。
            $comment = <<<EOT
本メールアドレス宛にパスワード再発行をいたしました。
下記のURLにて再発行パスワードをご入力いただき、ログインください。

ログインページ：http://localhost:8888/homepage/login.php
再発行パスワード: {$pass}
※ログイン後、パスワードのご変更をお願いいたします。

////////////////////////////////
イラストサイトカスタマーセンター
URL:
Email:
////////////////////////////////
EOT;

            sendMail($from, $to, $subject, $comment);

            //セッション削除
            session_unset();
            $_SESSION['msg_success'] = SUC00;
            debug('セッション変数の中身：'.print_r($_SESSION,true));

            header("Location:login.php"); //ログインページへ

          }else{
            debug('クエリに失敗しました。');
            $err_msg['common'] = MSG07;
          }

        }catch(Exception $e){
          error_log('エラー発生：'.$e->getMessage());
          $err_msg['common'] = MSG07;
        }
      }
    }
  }
}


?>
<?php
$siteTitle = 'パスワード再発行';
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
        <form action="" class="form" method="post">
          <p>ご指定のメールアドレスにお送りした【パスワード再発行認証メール】に記載されている「認証キー」をご入力ください。</p>
          <div class="err_msg_area">
            <p>
              <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
            </p>
          </div>
          <label for="" class="<?php if(!empty($err_msg['token'])) echo 'err'; ?>">
            認証キー
            <span class="err_msg_area"><?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?></span>
            <input type="text" name="token" value="<?php echo getFormData('token'); ?>">
          </label>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="再発行する">
          </div>
        </form>
      </div>
      <a href="passRemindSend.php" class="return">&lt; パスワード再発行メールを再度送信する</a>
    </section>
  </div>

  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
