<?php
//関数等読みこみ
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   トップページ');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログイン認証ファイル読み込み
require('auth.php');

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  画面処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//画面表示用データ取得
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//カレントページのuGETパラメータを取得
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1; //デフォルトは１ページ目
//パラメータに不正な値が入っているかチェック
if(!is_int((int)$currentPageNum)){
  error_log('エラー発生：指定ページに不正な値が入りました。');
  header("Location:index.php"); //トップページへ
}
//表示件数
$listSpan = 20;
//現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan); //１ページ目なら(1-1)*20 = 0、２ページ目なら(2-1)*20 = 20
//DBから商品データを取得
$dbProductData = getProductList($currentMinNum);
//DBからカテゴリデータを取得
$dbCategoryData = getTag();
debug('現在のページ：'.$currentPageNum);
//debug('フォーム用Dbデータ：'.print_r($dbFormData,true));
//debug('カテゴリデータ：'.print_r($dbCategoryData,true));

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

 ?>
<?php
$siteTitle = 'イラストサイト';
//head部分読み込み
require('head.php');
?>
<body>

  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>

  <!-- メインコンテンツ -->
  <div id="content" class="">
    <!-- レイアウト -->
    <div id="main-home">

      <?php
      foreach($dbProductData['data'] as $key => $val):
       ?>

      <div class="panel">
        <div class="panel-head">
          <a href="irustDetails.php?p_id=<?php echo $val['id']; ?>">
            <img
            src="<?php echo sanitize($val['pic1']); ?>" alt="<?php echo sanitize($val['title']); ?>">
          </a>
        </div>
        <div class="panel-body">
          <a href="irustDetails.php?p_id=<?php echo $val['id']; ?>">
            <?php echo sanitize($val['title']); ?>
          </a>
        </div>
      </div>
      <?php
        endForeach;
      ?>
  </div>

  <!-- ページネーション -->
  <div class="pagenation">
    <ul class="pagenation-list">
      <?php
        $pageColNum = 5;
        $totalPageNum = $dbProductData['total_page'];
        //現在のページが、総ページ数を同じ、かつ、総ページ数が表示項目数以上なら左にリンク４個だす
        if( $currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum){
          $minPageNum = $currentPageNum - 4;
          $maxPageNum = $currentPageNum;
        //現在のページが、総ページ数の１ページ前なら、左にリンクを３個、右に１個出す
      }elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum >= $pageColNum){
          $minPageNum = $currentPageNum - 3;
          $maxPageNum = $currentPageNum + 1;
        //現在ページが２の場合は左にリンク１個、右にリンク３個だす
      }elseif( $currentPageNum == 2 && $totalPageNum >= $pageColNum){
          $minPageNum = $currentPageNum - 1;
          $maxPageNum = $currentPageNum + 3;
        //現ページが１の場合は左に何も出さない。右に５個だす
      }elseif( $currentPageNum == 1 && $totalPageNum >= $pageColNum){
          $minPageNum = $currentPageNum;
          $maxPageNum = 5;
        //総ページ数が表示項目数より少ない場合は、総ページ数をループのMax、ループのMinを１に設定
      }elseif($totalPageNum < $pageColNum){
          $minPageNum = 1;
          $maxPageNum = $totalPageNum;
        //それ以外は左に２個だす
      }else{
        $minPageNum = $currentPageNum - 2;
        $maxPageNum = $currentPageNum + 2;
      }
      ?>
      <?php if($currentPageNum != 1): ?>
        <li class="list-item"><a href="?p=1">&lt;</a></li>
      <?php endif; ?>
      <?php
        for($i = $minPageNum; $i <= $maxPageNum; $i++):
      ?>
      <li class="list-item <?php if($currentPageNum == $i ) echo 'active'; ?>">
        <a href="?p=<?php echo $i; ?>"><?php echo $i; ?></a>
      </li>
      <?php
    endfor;
      ?>
      <?php if($currentPageNum != $maxPageNum): ?>
        <li class="list-item"><a href="?p=<?php echo $maxPageNum; ?>">&gt;</a></li>
      <?php endif; ?>
    </ul>
  </div>
</div>

  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
