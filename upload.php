<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Get Chart</title>
  </head>
  <body>
    <div id="chart" style="width: 1000px; height: 500px; margin: 10px auto;"></div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="../plotly-latest.min.js"></script>

    <!-- upload form -->
    <form action="upload.php" method="post" enctype="multipart/form-data" style="margin:50px;">
      <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
      <input type="file" name="upfile" />
      <input type="submit" value="アップロード" />
    </form>
    <!-- upload form -->

    <!-- ここからphp -->
    <?php
        //ファイル名確認
        $file_name = $_FILES["upfile"]["name"];
         
        //ファイルの仮アップロード先
        $tmp_path = $_FILES["upfile"]["tmp_name"]; //C:\xampp\tmp\php2816.tmp
        
        //保存先のパスを設定
        $upload_path = "C:/xampp/htdocs/data/";

        //仮のアップロード場所から保存先にファイルを移動
        if (is_uploaded_file($tmp_path)) {
            if (move_uploaded_file($tmp_path,"$upload_path".$file_name)){
            // ファイルが読出可能になるようにアクセス権限を変更
                chmod("$upload_path".$file_name, 0644);
            }
            else {
                echo "Error";
            }
        }

        //csvのデータを配列に格納

        // ファイルの中身を配列で取得
        //[Date,Timestamp,Open,High,Low,Close,Volume]で1セット（1行目はヘッダー）

        //ファイルを変数に入れる
        $csv_file = file_get_contents("$upload_path".$file_name);

        //変数を改行毎の配列に変換
        $aryHoge = explode("\n", $csv_file);

        $aryCsv = [];
        foreach($aryHoge as $key => $value){
            if($key == 0) continue; //1行目が見出しなど、取得したくない場合
            if(!$value) continue; //空白行が含まれていたら除外
            $aryCsv[] = explode(",", $value);
        }

        //配列を整形
        $data_xarray = []; //時間データ配列
        $data_yarray = []; //価格データ配列

        foreach($aryCsv as $key => $value){         
            $data_xarray[] = $aryCsv[$key][0]." ".$aryCsv[$key][1]; //Date+Timestamp
            $data_yarray[] = (float)$aryCsv[$key][5]; //Close 
        }

        //javascriptへ配列の受け渡し
        $json_dataname = json_encode($file_name);
        $json_xarray = json_encode($data_xarray);
        $json_yarray = json_encode($data_yarray);
    ?>
    <!-- php終わり -->
    
    <script type="text/javascript">
        let dataname = <?php echo $json_dataname; ?>;
        let data_xarray = <?php echo $json_xarray; ?>;
        let data_yarray = <?php echo $json_yarray; ?>;
    </script>
    <script src="../app.js"></script>  
  </body>
</html>