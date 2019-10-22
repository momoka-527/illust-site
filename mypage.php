<?php
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   マイページ');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログイン認証読み込み
require('auth.php');

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  画面表示用情報取得
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

$u_id = $_SESSION['user_id'];

$userDetail = getUserDetail($u_id);
$illustData = getIllust($u_id);
$bookmarkData = getBookmark($u_id);

debug('$userDetail:'.print_r($userDetail,true));

debug('取得したイラストデータ：$illustData:'.print_r($illustData,true));
debug('取得したお気に入りデータ：$bookmarkData:'.print_r($bookmarkData,true));
debug('取得したユーザーデータ：$userDetail:'.print_r($userDetail,true));

debug('デバッグ：画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>

<?php
$siteTitle = 'マイページ';
//head部分読み込み
require('head.php');
?>
<body>
  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>

  <p id="js-show-msg" class="msg-slide" style="display:none;">
    <?php echo getSessionFlash('msg_success'); ?>
  </p>
  <!-- メインコンテンツ -->
  <div id="content">
    <section id="main">
      <div class="mypage-prof">
        <div class="prof-img">
          <img src="<?php echo showImg(sanitize($userDetail['picture'])); ?>" alt="プロフィール画像">
        </div>
        <div class="prof-body">
          <div class="prof-item">
            <h2><?php echo sanitize($userDetail['nickname']); ?></h2>
            <div>
              <span>フォロー準備中</span>
            </div>
          </div>
          <div class="prof-item prof-item-btn">
            <div>
              <a href="profEdit.php" class="prof-btn">プロフィール編集</a>
            </div>
            <div>
              <a href="passEdit.php" class="prof-btn">パスワード変更</a>
            </div>
            <div>
              <a href="withdraw.php" class="prof-btn">退会する</a>
            </div>
          </div>
          <div class="prof-comment">
            <p><?php echo sanitize($userDetail['comment']); ?></p>
          </div>
        </div>
      </div>
      <div class="list">
        <h3>投稿作品</h3>

        <?php foreach($illustData as $key => $value): ?>

          <div>
            <div class="panel">
              <div class="panel-head">
                <a href="irustDetails.php?p_id=<?php echo $value['id']; ?>">
                  <img src="<?php echo showImg(sanitize($value['pic1'])); ?>" alt="">
                </a>
              </div>
              <div class="panel-body">
                <a href="irustDetails.php?p_id=<?php echo $value['id']; ?>"><?php echo mb_strimwidth(sanitize($value['title']), 0, 20, '…', 'UTF-8' ); ?></a>
              </div>
            </div>
          </div>

        <?php endforeach; ?>

      </div>

      <div class="list">
        <h3>ブックマーク</h3>

        <?php foreach($bookmarkData as $key => $value): ?>

        <div>
            <div class="panel">
              <div class="panel-head">
                <a href="irustDetails.php?p_id=<?php echo $value['illustration_id']; ?>">
                  <img src="<?php echo showImg(sanitize($value['pic1'])) ?>" alt="">
                </a>
              </div>
              <div class="panel-body">
                <div>
                  <a href="irustDetails.php?p_id=<?php echo $value['illustration_id']; ?>"><?php echo mb_strimwidth(sanitize($value['title']), 0, 20, '…', 'UTF-8' );  ?></a>
                </div>

              </div>
            </div>
        </div>

      <?php endforeach; ?>
      </div>
    </section>
  </div>

  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
