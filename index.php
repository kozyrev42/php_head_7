<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Mismatch - приложение</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
    <h3>Mismatch - Где противоположности притягиваются!</h3>

    <?php
  require_once('appvars.php');
  require_once('connectvars.php');

  // меню навигации зависит от входа
  if (isset($_COOKIE['username'])) {
    echo '&#10084; <a href="viewprofile.php"> Посмотреть профиль </a><br />';
    echo '&#10084; <a href="editprofile.php"> Редактировать профиль </a><br />';
    echo '&#10084; <a href="logout.php"> Выход из приложения ('. $_COOKIE ['username'] .') </a><br />';
  }
  else {
    echo '&#10084; <a href="login.php"> Вход в приложение </a><br />';
    echo '&#10084; <a href="signup.php"> Создание учетной записи </a><br />';
  }
    

  // подключение к базе 
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 

  // запрос на частичную информацию из таблицы
  $query = "SELECT user_id, first_name, picture FROM mismatch_user WHERE first_name IS NOT NULL ORDER BY join_date DESC LIMIT 5";
  $data = mysqli_query($dbc, $query);

  echo '<h4>Последние участники:</h4>';
  // Перебрать массив пользовательских данных, отформатировав его как HTML
  echo '<table>';
  while ($row = mysqli_fetch_array($data)) {
    // is_file() - функия определяет, является объект обычным файлом
    // filesize() - функция возвращает размер файла в байтах
    if (is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture']) > 0) {
        echo '<tr><td><img src="' . MM_UPLOADPATH . $row['picture'] . '" alt="' . $row['first_name'] . '" /></td>';
    }
    else {
        // если пользователь не загрузил фото, выводим балванку
        echo '<tr><td><img src="' . MM_UPLOADPATH . 'nopic.jpg' . '" alt="' . $row['first_name'] . '" /></td>';
    }
    echo '<td>' . $row['first_name'] . '</td></tr>';
  }
  echo '</table>';

  mysqli_close($dbc);
?>

</body>

</html>