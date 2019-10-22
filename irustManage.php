<?php
//関数等読みこみ
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   イラスト管理画面');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログイン認証ファイル読み込み
require('auth.php');

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  画面表示処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//画面表示用データ取得
$u_id = $_SESSION['user_id'];

$illustData = getIllust($u_id);

debug('取得したデータ：$illustData:'.print_r($illustData,true));

debug('デバッグ：画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
 ?>

<?php
$siteTitle = 'イラスト管理画面';
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
      <div class="work-manage">
        <h2>作品管理</h2>
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
                  <div>
                    <a href="irustDetails.php?p_id=<?php echo $value['id']; ?>"><?php echo mb_strimwidth(sanitize($value['title']), 0, 20, '…', 'UTF-8' ); ?></a>
                  </div>
                  <div>
                    <a href="irustEdit.php?p_id=<?php echo $value['id']; ?>">編集する</a>
                  </div>
                  <div>
                    <a href="#">削除</a>
                  </div>
                </div>
              </div>
            </div>

          <?php endforeach; ?>

        </div>

      </div>
      <a href="#" class="return">&lt; マイページへ戻る</a>
    </section>
  </div>

  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
