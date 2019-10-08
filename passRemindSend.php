<?php
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   パスワード再発行メール送信ページ');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログインできない人が使う画面のため、ログイン認証はなし

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  画面処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//POST送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POSTの中身：'.print_r($_POST,true));

  //変数にPOSTの中身を代入
  $email = $_POST['email'];

  //未入力チェック
  validRequired($email, 'email');

  if(empty($err_msg)){
    debug('未入力チェッククリア');

    //emailの型式チェック
    validEmail($email, 'email');
    //emailの最大文字数チェック
    validMaxLen($email, 'email');

    if(empty($err_msg)){
      debug('バリデーションクリア');

      //例外処理
      try{
        //DBへ接続
        $dbh = dbConnect();
        //SQL文作成
        $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(':email' => $email);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        //クエリ結果を取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        //EmailがDBに登録されている場合
        if($stmt && array_shift($result)){
          debug('クエリ成功、DB登録あり');
          $_SESSION['msg_success'] = SUC03;

          $auth_key = makeRandKey(); //認証キー生成

          //メールを送信する
          $from = 'test@test.com';
          $to = $email;
          $subject = ' 【パスワード再発行認証】 | イラストサイト';
          //EOTはEndOfFileの略。ABCでもなんでもOK。先頭の<<<の後の文字列と合わせること。EOTの前後に空白等何も入れてはいけない
          //EOT内の半角空白もそのまま半角空白として扱われるので、インデントはしないこと。
          $comment = <<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーをご入力いただくと、パスワードが発行されます。

パスワード再発行認証キー入力メッセージ：http://localhost:8888/homepage/passRemindRecieve.php
認証キー： {$auth_key}
※認証キーの有効期限は30分となります。

認証キーを再発行されたい場合は、下記ページより再度発行をお願いいたします。
http://localhost:8888/homepage/passRemindSend.php

////////////////////////////////
イラストサイトカスタマーセンター
URL:
Email:
////////////////////////////////
EOT;
          sendMail($from, $to, $subject, $comment);

          //認証に必要な情報をセッションへ保存
          $_SESSION['auth_key'] = $auth_key;
          $_SESSION['auth_email'] = $email;
          $_SESSION['auth_key_limit'] = time()+(60*30); //現在時刻より30分後のUNIXタイムスタンプを入れる
          debug('セッション変数の中身：'.print_r($_SESSION,true));

          header("Location:passRemindRecieve.php"); //認証キー入力ページへ

        }else{
          debug('クエリに失敗したかDBに登録のないEmailが入力されました。');
          $err_msg['common'] = MSG07;
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
          <p>ご指定のメールアドレス宛にパスワード再発行用のURLと認証キーをお送り致します。</p>
          <div class="err_msg_area">
            <p>
              <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
            </p>
          </div>
          <label for="" class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
            メールアドレス
            <span class="err_msg_area"><?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?></span>
            <input type="text" name="email" value="<?php echo getFormData('email'); ?>">
          </label>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="送信する">
          </div>
        </form>
      </div>
      <a href="index.php" class="return">&lt; トップページへ戻る</a>
    </section>
  </div>

  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
