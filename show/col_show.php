<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width">
  <title>Ajax Example</title>
</head>

<body>
  <h2>コレクション一覧</h2>

  <table border="1">


    <?php
    ini_set('display_errors', 1);
    $flagType = $_GET['type'];
    if ($flagType == "esp32") {
    ?>
      <tr>
        <th>登録時刻</th>
        <th>ESP32名</th>
      </tr>

      <?php
      $query = $_GET['q'];
      echo "<h2>検索条件 : " . $query . "</h2>";
      $url = "http://192.168.100.60:5000/return_data_esp32?col=" . $query;
      $json = file_get_contents($url);
      $params = json_decode($json, true);
      #echo var_dump($params);
      $nameList = array();
      $timeList = array();
      foreach ($params as $key => $value) {
        foreach ($value as $key2 => $value2) {
          #echo $key . "=>" . $value2 . "<br>";
          if ($key == "time") {
            array_push($timeList, $value2);
          }
          if ($key == "name") {
            array_push($nameList, $value2);
          }
        }
      }
      for ($i = 0; $i < count($timeList); $i++) {
        echo "<tr>";
        echo "<td>" . $timeList[$i] . "</td><td>" . $nameList[$i] . "</td>";
        echo "</tr>";
      }
    } elseif ($flagType == "sensor") {
      ?>
      <tr>
        <th>登録時刻</th>
        <th>センサー名</th>
        <th>個数</th>
      </tr>

    <?php
      $query = $_GET['q'];
      echo "<h2>検索条件 : " . $query . "</h2>";
      $url = "http://192.168.100.60:5000/return_data_sensor?col=" . $query;
      $json = file_get_contents($url);
      $params = json_decode($json, true);
      #echo var_dump($params);
      $nameList = array();
      $timeList = array();
      $amountList = array();
      foreach ($params as $key => $value) {
        foreach ($value as $key2 => $value2) {
          #echo $key . "=>" . $value2 . "<br>";
          if ($key == "time") {
            array_push($timeList, $value2);
          }
          if ($key == "name") {
            array_push($nameList, $value2);
          }
          if ($key == "amount") {
            array_push($amountList, $value2);
          }
        }
      }
      for ($i = 0; $i < count($timeList); $i++) {
        echo "<tr>";
        echo "<td>" . $timeList[$i] . "</td><td>" . $nameList[$i] . "</td><td>" . $amountList[$i] . "</td>";
        echo "</tr>";
      }
    }

    ?>

  </table>
  <?php
  if ($_GET["type"] == "esp32") {
  ?>
    <input type="button" onclick="location.href='http://192.168.100.60:5000/delete_col?type=esp32&col=<?php echo $query; ?>'" value="データを全削除" id="checkDelete">
  <?php
  } elseif ($_GET["type"] == "sensor") {
  ?>
    <input type="button" onclick="location.href='http://192.168.100.60:5000/delete_col?type=sensor&col=<?php echo $query; ?>'" value="データを全削除" id="checkDelete">
  <?php
  }
  ?>

</body>

</html>