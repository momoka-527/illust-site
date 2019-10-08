<?php

//関数等読みこみ
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   イラスト投稿ページ');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログイン認証ファイル読み込み
require('auth.php');

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  画面処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//POST送信時処理
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：'.print_r($_POST,true));
  debug('FILE情報：'.print_r($_FILES,true));

  //画像をアップロードし、パスを格納
  $pic1 = ( !empty($_FILES['pic1']['name']) ) ? uploadImg($_FILES['pic1'], 'pic1') : '';
  //画像をPOSTしていない(登録していない)が既にDBに登録されている場合、DBのパスを入れる(POSTには反映されないので)
  $pic2 = ( !empty($_FILES['pic2']['name']) ) ? uploadImg($_FILES['pic2'], 'pic2') : '';
  $pic3 = ( !empty($_FILES['pic3']['name']) ) ? uploadImg($_FILES['pic3'], 'pic3') : '';
  $title = $_POST['title'];
  $tag = ( !empty($_POST['tag']) ) ? $_POST['tag'] : '';
  $comment = $_POST['comment'];

    //バリデーションチェック
    //未入力チェック
    validRequired($title, 'title');
    //最大文字数チェック
    validMaxLen($title, 'title');
    //最大文字数チェック
    validMaxLen($comment, 'comment', 500);
    //最大文字数チェック
    validMaxLen($tag, 'tag');


      //未入力チェック
      validRequired($title, 'title');
      //最大文字数チェック
      validMaxLen($title, 'title');


  if(empty($err_msg)){
    debug('バリデーションクリア');
    debug('イラストをDB新規登録します。');

    //例外処理
    try{
      //DBへ接続
      $dbh = dbConnect();
      //SQL文作成

        $sql = 'INSERT INTO illustration (title, tag, comment, pic1, pic2, pic3, user_id, create_date)
          VALUES (:title, :tag, :comment, :pic1, :pic2, :pic3, :user_id, :create_date)';
        $data = array(':title' => $title, ':tag' => $tag, ':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2,
          ':pic3' => $pic3, ':user_id' => $_SESSION['user_id'], ':create_date' => date('Y-m-d H:i:s'));
      debug('SQL:'.$sql);
      debug('流し込みデータ：'.print_r($data,true));
      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      //クエリ成功の場合
      if($stmt){
        $_SESSION['msg_success'] = SUC04;
        debug('マイページへ遷移します。');
        header("Location:mypage.php"); //マイページへ遷移
      }
    }catch(Exception $e){
      debug('エラー発生：'.$e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }

}
?>

<?php
$siteTitle = 'イラスト投稿画面';
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
        <form class="form" action="" method="post" enctype="multipart/form-data">
          <div>
            <h2>作品投稿</h2>
            <div class="err_msg_area" style="overflow:hidden">
              <p>
                <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
              </p>
            </div>
            <div class="imgdrop-container">
              画像1 画像をドラッグ＆ドロップしてください。
              <p class="err_msg_area"><?php if(!empty($err_msg['pic1'])) echo $err_msg['pic1']; ?></p>
              <label for="" class="area-drop <?php if(!empty($err_msg['pic1'])) echo 'err'; ?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728"> <!-- ファイルの最大サイズ指定 3145728 = 3メガ  -->
                <input type="file" name="pic1" class="input-file">
                <img src="<?php echo getFormData('pic1'); ?>" alt="" class="prev-img"
                      style="<?php if(empty(getFormData('pic1'))) echo 'display:none'; ?>">
              </label>
            </div>

            <div class="imgdrop-container">
              画像2 画像をドラッグ＆ドロップしてください。
              <label for="" class="area-drop <?php if(!empty($err_msg['pic2'])) echo 'err'; ?>">

                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic2" class="input-file">
                <img src="<?php echo getFormData('pic2'); ?>" alt="" class="prev-img"
                      style="<?php if(empty(getFormData('pic2'))) echo 'display:none'; ?>">

              </label>
            </div>
            </label>
            <div class="imgdrop-container">
              画像3 画像をドラッグ＆ドロップしてください。
              <label for="" class="area-drop <?php if(!empty($err_msg['pic3'])) echo 'err'; ?>">

                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic3" class="input-file">
                <img src="<?php echo getFormData('pic3'); ?>" alt="" class="prev-img"
                      style="<?php if(empty(getFormData('pic3'))) echo 'display:none'; ?>">

              </label>
            </div>

          </div>
          <div>
            <label for="" class="<?php if(!empty($err_msg['title'])) echo 'err'; ?>">
              タイトル
              <span class="err_msg_area"><?php if(!empty($err_msg['title'])) echo $err_msg['title']; ?></span>
              <input type="text" name="title" value="<?php echo getFormData('title'); ?>">
            </label>
            <label for="" style="overflow:hidden;" class="<?php if(!empty($err_msg['comment'])) echo 'err'; ?>">
              コメント
              <span class="err_msg_area"><?php if(!empty($err_msg['comment'])) echo $err_msg['comment']; ?></span>
              <textarea name="comment" id="js-count" rows="8" cols="80"></textarea>
              <p class="counter-text" style="float:right;"><span id="js-count-view">0</span>/500文字</p>
            </label>
            <label for="" class="<?php if(!empty($err_msg['tag'])) echo 'err'; ?>">
              タグ
              <span class="err_msg_area"><?php if(!empty($err_msg['tag'])) echo $err_msg['tag']; ?></span>
              <input type="text" name="tag" value="<?php echo getFormData('tag'); ?>">
            </label>
          </div>
          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="投稿する">
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
