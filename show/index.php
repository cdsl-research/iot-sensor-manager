<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="index.css">
    <script src="https://code.jquery.com/jquery-1.6.4.js"></script>
    <title>コレクション一覧</title>
  </head>
  <body>
    <h2>コレクション一覧</h2>
    <hr>
    <?php
      ini_set('display_errors', 1);
      $url = "http://192.168.100.60:5000/return_list";
      $json = file_get_contents($url);
      $params = json_decode($json, true);
      #echo var_dump($params);
      foreach($params as $key => $value){
        foreach($value as $key2 => $value2){
          echo '<h3>' . $value2 . '</h3>';
          echo '<a href="http://192.168.100.60/show/col_show.php?q=' . $value2 . '&type=esp32">esp32</a><br />';
          echo '<a href="http://192.168.100.60/show/col_show.php?q=' . $value2 . '&type=sensor">sensor</a>';
    ?>
      <!-- 折り畳み展開ポインタ -->
      <div onclick="obj=document.getElementById('open').style; obj.display=(obj.display=='none')?'block':'none';">
          <a style="cursor:pointer;">▼ クリックでメニュー展開</a>
          </div>
          <!--// 折り畳み展開ポインタ -->

          <!-- 折り畳まれ部分 -->
          <div id="open" style="display:none;clear:both;">

          

    <?php
          echo '<a href="http://192.168.100.60/register">追加</a> |||';
          echo '<a href="http://192.168.100.60:5000/delete_col?type=esp32&col='. $value2 .'">削除</a><br />';
          echo '</div>';
          echo '<hr>';
        }
      }

    ?>
    
    <!--// 折り畳まれ部分 -->
  </body>
</html>