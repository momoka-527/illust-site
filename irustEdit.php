<?php
//関数等読みこみ
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   作品編集画面');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログイン認証ファイル読み込み
require('auth.php');

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  画面表示処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//画面表示用データ取得
$u_id = $_SESSION['user_id'];
//GETパラメータ取得
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
debug('$p_id:'.print_r($p_id,true));

$dbFormData = getProduct($u_id, $p_id);

debug('取得したデータ：$illustData:'.print_r($dbFormData,true));

//パラメータに不正な値が入っているかチェック
if(empty($dbFormData)){
  error_log('エラー発生：指定ページに不正な値が入りました。');
  header('Location:index.php'); //トップページへ
}

debug('デバッグ：画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
 ?>

<?php
$siteTitle = 'イラスト編集画面';
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
      <div class="irustEdit-form-container">
        <h2>作品編集</h2>
        <div class="irustEdit-form">
          <form action="" method="post" class="form">
            <div class="">
              <div class="imgdrop-container">
                画像1  クリックまたはドラッグ＆ドロップ
                <label for="" class="area-drop">
                  <input type="hidden" name="MAX_FILE_SIZE" value="3145728"> <!-- ファイルの最大サイズ指定 3145728 = 3メガ  -->
                  <input type="file" name="picture" class="input-file">
                  <img src="<?php echo getFormData('pic1'); ?>" alt="" class="prev-img"
                        style="<?php if(empty(getFormData('pic1'))) echo 'display:none'; ?>">
                </label>
                画像2  クリックまたはドラッグ＆ドロップ
                <label for="" class="area-drop">
                  <input type="hidden" name="MAX_FILE_SIZE" value="3145728"> <!-- ファイルの最大サイズ指定 3145728 = 3メガ  -->
                  <input type="file" name="picture" class="input-file">
                  <img src="<?php echo getFormData('pic2'); ?>" alt="" class="prev-img"
                        style="<?php if(empty(getFormData('pic2'))) echo 'display:none'; ?>">
                </label>
                画像3  クリックまたはドラッグ＆ドロップ
                <label for="" class="area-drop">
                  <input type="hidden" name="MAX_FILE_SIZE" value="3145728"> <!-- ファイルの最大サイズ指定 3145728 = 3メガ  -->
                  <input type="file" name="picture" class="input-file">
                  <img src="<?php echo getFormData('pic3'); ?>" alt="" class="prev-img"
                        style="<?php if(empty(getFormData('pic3'))) echo 'display:none'; ?>">
                </label>
              </div>
            </div>
            <div>
              <div>
                <p>タイトル変更</p>
                <input type="text" name="title" value="<?php echo getFormData('title'); ?>">
              </div>
              <div>
                <p>タグ編集</p>
                <input type="text" name="tag" value="<?php echo getFormData('tag'); ?>">
              </div>
              <div class="coment-form">
                <p>コメント編集</p>
                <textarea id="js-count" name="comment" rows="8" cols="80"><?php echo getFormData('comment'); ?></textarea>
                <p class="counter-text" style="float:right;"><span id="js-count-view">0</span>/500文字</p>
              </div>
            </div>

            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="更新する">
            </div>
          </form>
        </div>

      </div>
      <a href="mypage.php" class="return">&lt; マイページへ戻る</a>
    </section>
  </div>

  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
