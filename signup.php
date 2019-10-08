<?php

require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   ユーザー登録ページ');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//POSTされているかチェック
if(!empty($_POST)){

  //変数に代入
  $nickname = $_POST['nickname'];
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  //バリデーションチェック
  //未入力チェック
  validRequired($nickname, 'nickname');
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');

  if(empty($err_msg)){

    //Email
    //Email型式チェック
    validEmail($email, 'email');
    //最大文字数チェック
    validMaxLen($email, 'email');
    //email重複チェック
    validEmailDup($email);

    //パスワード
    //最大文字数チェック
    validMaxLen($pass, 'pass');
    //最小文字数チェック
    validMinLen($pass, 'pass');
    //半角英数字チェック
    validHalf($pass, 'pass');

    //パスワード再入力
    //最大文字数チェック
    validMaxLen($pass_re, 'pass_re');
    //最小文字数チェック
    validMinLen($pass_re, 'pass_re');
    //パスワード再入力
    validPassMatch($pass, $pass_re, 'pass_re');



    //エラーコードに何も入らなかった場合(バリデーションチェッククリア)
    if(empty($err_msg)){
      //例外処理
      try{
        //DB接続
        $dbh = dbConnect();
        //SQL文作成
        $sql = 'INSERT INTO users (nickname,email,password,login_time,create_date) VALUES(:nickname,:email,:pass,:login_time,:create_date)';
        $data = array(':nickname' => $nickname, ':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                      ':login_time' => date('Y-m-d H:i:s'),
                      ':create_date' => date('Y-m-d H:i:s')); //date関数 = ローカルの日付/時刻を書式化する
        //SQL実行
        $stmt = queryPost($dbh, $sql, $data);

        //クエリ成功した場合
        if($stmt){
          //ログイン有効期限(デフォルト一時間)
          $sesLimit = 60*60;
          //最終ログイン日時を現在日時に設定
          $_SESSION['login_date'] = time();
          $_SESSION['login_limit'] = $sesLimit; //ログイン有効期限もセットする
          //ユーザーIDを格納
          $_SESSION['user_id'] = $dbh->lastInsertId();
          //PDOオブジェクトの中にはlastInsertId()メソッドが入っているので、それを呼び出す
          //lastInsertId()...直前でインサートしたレコードのIDを取得できる

          debug('セッション変数の中身：'.print_r($_SESSION,true));

          header("Location:mypage.php"); //マイページへ遷移

  //      }else{
  //        error_log('クエリに失敗しました。');
  //        $err_msg['common'] = MSG07;
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
 $siteTitle = 'ユーザー新規登録';
 //head部分読み込み
 require('head.php');
 ?>
<body>
  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>

    <div id="content">

      <section id="main">

        <div class="form-container">
          <h2>ユーザー登録</h2>
          <form action="" class="form" method="post">
            <div class="err_msg_area">
              <p>
                <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
              </p>
            </div>
            <label for="" class="<?php if(!empty($err_msg['nickname'])) echo 'err' ?>">
              *ニックネーム
              <span class="err_msg_area"><?php if(!empty($err_msg['nickname'])) echo $err_msg['nickname']; ?></span>
              <input type="text" name="nickname" value="<?php if(!empty($_POST['nickname'])) echo $_POST['nickname']; ?>">
            </label>
            <label for="" class="<?php if(!empty($err_msg['email'])) echo 'err' ?>">
              *メールアドレス
              <span class="err_msg_area"><?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?></span>
              <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
            </label>
            <label for="" class="<?php if(!empty($err_msg['pass'])) echo 'err' ?>">
              *パスワード
              <span>※英数字６文字以上</span>
              <span class="err_msg_area"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?></span>
              <input type="password" name="pass">
            </label>
            <label for="" class="<?php if(!empty($err_msg['pass_re'])) echo 'err' ?>">
              *パスワード(再入力)
              <span class="err_msg_area"><?php if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re']; ?></span>
              <input type="password" name="pass_re">
            </label>
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="登録">
            </div>
          </form>
        </div>
      </section>
    </div>
    <!-- フッター -->
    <?php
    require('footer.php');
    ?>
