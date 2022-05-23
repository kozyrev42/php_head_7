<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html" />
    <meta charset="utf-8">
    <title>Mismatch - приложение</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
    <h3>Mismatch - Где противоположности притягиваются!</h3>

<?php
  
  require_once('testPDO.php');
  

  // запрос на частичную информацию из таблицы
  $query = "SELECT user_id, first_name, picture FROM mismatch_user WHERE first_name IS NOT NULL ORDER BY join_date DESC LIMIT 5";
  
  // подключение к базе, отправка запроса методом query, результат сохраняется в переменной
  $data = $pdo->query($query);

  


  echo '<h4>Последние участники:</h4>';
  // Перебрать массив пользовательских данных, отформатировав его как HTML
  echo '<table>';
  while ($row = mysqli_fetch_array($data)) {
    // is_file() - функия определяет, является объект обычным файлом
    // filesize() - функция возвращает размер файла в байтах
    if (is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture']) > 0) {
        // вывод фото участников
        echo '<tr><td><img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="' . $row['first_name'] . '" /></td>';
    }
    else {
        // если пользователь не загрузил фото, выводим балванку
        echo '<tr><td><img src="' . MM_UPLOADPATH . 'nopic.jpg' . '" alt="' . $row['first_name'] . '" /></td>';
    }

    // если Пользователь Вошел, доступны ссылки для просмотра информации о других пользователей
    if (isset($_SESSION['user_id'])) {
      // с переходом по ссылке, отправляем с GET, данные о просматриваемом участнике
      echo '<td><a href="viewprofile.php?user_id=' . $row['user_id'] . '">' . $row['first_name'] . '</a></td></tr>';
    }
    else { 
      // просто вывод имён
      echo '<td>' . $row['first_name'] . '</td></tr>';
    }
    


  }
  echo '</table>';

  
?>

</body>

</html>