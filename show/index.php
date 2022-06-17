<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="index.css">
  <link rel="stylesheet" type="text/css" href="bottun_style.css">
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
  foreach ($params as $key => $value) {
    foreach ($value as $key2 => $value2) {
  ?>

      <h3><?php echo $value2; ?></h3>
      <div class="wrap">
        <div class="box"><input type="button" onclick="location.href='http://192.168.100.60/show/col_show.php?q=<?php echo $value2; ?>&type=esp32'" value="esp32リストを参照"></div>
        <div class="box"><input type="button" onclick="location.href='http://192.168.100.60/show/col_show.php?q=<?php echo $value2; ?>&type=sensor'" value="Sensorリストを参照"></div>
        <div class="box"><input type="button" onclick="location.href='http://192.168.100.60/register'" value="ESP32 or センサーを追加"></div>
      </div>
      <hr>

  <?php
    }
  }
  ?>
</body>

</html>