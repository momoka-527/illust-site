<footer class="footer">
  copyright <a href="index.php">イラストサイト</a>. All Rights Reserved.
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  $(function(){

    //メッセージ表示
    var $jsShowMsg = $('#js-show-msg'); //DOMを格納するための変数には頭に＄をつけること
    var msg = $jsShowMsg.text();
    if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){ //空白は判定しないように
      $jsShowMsg.slideToggle('slow');
      setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 5000);
      }

    //画像ライブプレビュー
    var $dropArea = $('.area-drop');
    var $fileInput = $('.input-file');
    $dropArea.on('dragover', function(e){ //第1引数にイベント名を指定  dragover = ドラッグして上に乗った時
      e.stopPropagation();
      e.preventDefault();   //余計なイベントを排除するもの
      $(this).css('border', '3px #595657 dashed');
    });
    $dropArea.on('dragleave', function(e){ //dragleave = ドラッグして離した時
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', 'none');
    });
    $fileInput.on('change', function(e){  //change = この場合だと$fileInputの中身が変わったら
      $dropArea.css('border', 'none');
      var file = this.files[0],                     //2,files配列にファイルが入っています
          $img = $(this).siblings('.prev-img'),     //3,jQueryのsiblingsメソッドで兄弟のimgを取得
          fileReader = new FileReader();            //4,ファイルを読み込むFileReaderオブジェクト

      //5,読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
      fileReader.onload = function(event){ //onload = 読み込みが完了した時  画像自体は引数のeventに入る
        //読み込んだデータをimgに設定
        $img.attr('src',event.target.result).show();
      };

      //6,画像読み込み
      fileReader.readAsDataURL(file); //画像を読み込んでいるのではなく、画像ファイル自体をDataURLに変換している
    });
    //テキストエリアカウント
    var $countUp = $('#js-count'),
        $countView = $('#js-count-view');
    $countUp.on('keyup',function(e){  //キーが離された時
      $countView.html($(this).val().length);
    });

    //画像切り替え
    var $switchImgSubs = $('.js-switch-img-sub'),
        $switchImgMain = $('#js-switch-img-main');
    $switchImgSubs.on('click',function(e){
      $switchImgMain.attr('src',$(this).attr('src')); //this＝クリックしたもの
    });

    //お気に入り登録、削除
    var $like,
        likeIllustId;
    $like = $('.js-click-like') || null; //値がない場合にjsが自動的に入れるundefinedが入らないようにnull入れる
    likeIllustId = $like.data('illustid') || null;

    if(likeIllustId !== undefined && likeIllustId !== null){
      $like.on('click',function(){
        var $this = $(this);
        $.ajax({
          type: "POST",
          url: "ajaxLike.php",
          data: { illustId : likeIllustId }
        }).done(function( data ){
          //クラス属性をtoggleで付け外しする
          console.log('Ajax success');
          $this.toggleClass('active');
        }).fail(function( msg ){
          console.log('Ajax Error');
        })
      })
    }
    });
</script>
</body>
</html>
