<?php
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   プロフィール編集ページ');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログイン認証
require('auth.php');

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
// 画面処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//DBからユーザー情報を取得する
$dbFormData = getUser($_SESSION['user_id']);

debug('取得したユーザーの情報：'.print_r($_SESSION,true));

//POST送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：'.print_r($_POST,true));
  debug('FILE情報：'.print_r($_FILES,true));

  //変数にユーザー情報を代入
  $nickname = $_POST['nickname'];
  $email = $_POST['email'];
  $picture = ( !empty($_FILES['picture']['name']) ) ? uploadImg($_FILES['picture'], 'picture') : '';
  $comment = $_POST['comment'];
  debug('$picture:'.print_r($picture,true));

  //DBの情報を入力情報が異なる場合にバリデーションを行う
  //$dbFormDataの中のDBから撮ってきたユーザー情報と比べる
  if($dbFormData['nickname'] !== $nickname){
    validMaxLen($nickname, 'nickname');
    validRequired($nickname, 'nickname');
  }
  if($dbFormData['email'] !== $email){
    validMaxLen($email, 'email');
    if(empty($err_msg['email'])){
      validEmailDup($email);
    }
    validEmail($email, 'email');
    validRequired($email, 'email');
  }
  /*if($dbFormData['picture'] !== $pic){

  }*/
  if($dbFormData['comment'] !== $comment){
    validMaxLen($comment, 'comment');
  }
  if(empty($err_msg)){
    debug('バリデーションチェッククリア');

    //例外処理
    try{
      //DB接続
      $dbh = dbConnect();
      //SQL文作成
      $sql = 'UPDATE users SET nickname = :nickname, email = :email, picture = :picture, comment = :comment WHERE id = :user_id';
      $data = array(':nickname' => $nickname, ':email' => $email, ':picture' => $picture, ':comment' => $comment, ':user_id' => $dbFormData['id']);

      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      //クエリ成功の場合
      if($stmt){
        debug('クエリ成功');
        $_SESSION['msg_success'] = SUC02;
        debug('マイページへ遷移します');
        header("Location:mypage.php"); //マイページへ
      //クエリ失敗の場合
//      }else{
//        debug('クエリ失敗');
//        $err_msg['common'] = MSG07;
      }
    }catch(Exception $e){
      error_log('エラー発生：'.$e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<?php
$siteTitle = 'プロフィール編集画面';
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
        <form action="" class="form" method="post" enctype="multipart/form-data">
          <h2>
            プロフィール編集
          </h2>
          <div class="err_msg_area">
            <p>
              <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
            </p>
          </div>
          <label class="<?php if(!empty($err_msg['nickname'])) echo 'err' ?>">
            ニックネーム変更
            <span class="err_msg_area"><?php if(!empty($err_msg['nickname'])) echo $err_msg['nickname']; ?></span>
            <input type="text" name="nickname" value="<?php echo getFormData('nickname') ?>">
          </label>
          <label class="<?php if(!empty($err_msg['email'])) echo 'err' ?>">
            メールアドレス変更
            <span class="err_msg_area"><?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?></span>
            <input type="text" name="email" value="<?php echo getFormData('email') ?>">
          </label>

          <div class="imgdrop-container">
            プロフィール画像 画像をドラッグ＆ドロップしてください。
            <label for="" class="area-drop">
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728"> <!-- ファイルの最大サイズ指定 3145728 = 3メガ  -->
              <input type="file" name="picture" class="input-file">
              <img src="<?php echo getFormData('picture'); ?>" alt="" class="prev-img"
                    style="<?php if(empty(getFormData('picture'))) echo 'display:none'; ?>">
            </label>
          </div>

          <label class="<?php if(!empty($err_msg['comment'])) echo 'err' ?>">
            自己紹介
            <span class="err_msg_area"><?php if(!empty($err_msg['comment'])) echo $err_msg['comment']; ?></span>
            <textarea name="comment" rows="8" cols="80"><?php echo getFormData('comment') ?></textarea>
          </label>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="更新する">
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
