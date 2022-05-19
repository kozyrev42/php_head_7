<?php
session_start(); // открытие соединения для доступа к переменным Сессии

// Если переменные Сессии не установлены, попробуем установить их из cookie
if (!isset($_SESSION['user_id'])) {
   if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
      $_SESSION['user_id'] = $_COOKIE['user_id'];
      $_SESSION['username'] = $_COOKIE['username'];
   }
}
?>

<!DOCTYPE html>
<html>

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Mismatch - Просмотр профиля</title>
   <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
   <h3> Веб-приложение - Просмотр профиля</h3>

   <?php
   require_once('appvars.php');
   require_once('connectvars.php');

   
   if (!isset($_SESSION['user_id'])) {
      // если вход не выполнен, прозьба войти
      echo '<p class="login"> Пожалуйста <a href="login.php"> Авторизуйтесь </a> для просмотра страницы.</p>';
      // останавливаем дальнейшее выполнение сценария
      exit();
   } else {
      // если вход выполнен
      echo ('<p class="login"> Вы зашли как ' . $_SESSION['username'] . '. <a href="logout.php"> Выйти </a>.</p>');
   }


   // подключение к базе данных
   $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

   // запрос зависит от того откуда открыт скрипт
   if (isset($_GET['user_id'])) { // скрипт открыт по ссылке, для просмотра Другой анкеты. $_GET['user_id'] - данные другой анкеты
      $query = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_GET['user_id'] . "'";
   }
   else {  // скрипт открыт для просмотра своей Анкеты
      $query = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
   }
   
   $data = mysqli_query($dbc, $query);

   // mysqli_num_rows() - функция Возвращает число рядов в результирующей выборке.
   if (mysqli_num_rows($data) == 1) {
      // Пользовательская строка была найдена, поэтому отображаются пользовательские данные
      $row = mysqli_fetch_array($data);
      echo '<table>';

      if (!empty($row['username'])) {
         echo '<tr><td class="label">Username:</td><td>' . $row['username'] . '</td></tr>';
      }

      if (!empty($row['first_name'])) {
         echo '<tr><td class="label">First name:</td><td>' . $row['first_name'] . '</td></tr>';
      }

      if (!empty($row['last_name'])) {
         echo '<tr><td class="label">Last name:</td><td>' . $row['last_name'] . '</td></tr>';
      }

      if (!empty($row['gender'])) {
         echo '<tr><td class="label">Gender:</td><td>';
         if ($row['gender'] == 'M') {
            echo 'Male';
         } else if ($row['gender'] == 'F') {
            echo 'Female';
         } else {
            echo '?';
         }
         echo '</td></tr>';
      }

      if (!empty($row['birthdate'])) {
         if (!isset($_GET['user_id']) || ($user_id == $_GET['user_id'])) {
            // Показывать пользователю его собственную дату рождения
            echo '<tr><td class="label">Birthdate:</td><td>' . $row['birthdate'] . '</td></tr>';
         } else {
            // Показывать только год рождения для всех остальных
            // list() - Присваивает полученным переменным значения
            // explode() - Возвращает массив (array) строк (string), созданный делением параметра "$row['birthdate']" по границам, указанным параметром '-'.
            list($year, $month, $day) = explode('-', $row['birthdate']);
            echo '<tr><td class="label">Year born:</td><td>' . $year . '</td></tr>';
         }
      }

      // отбражение города и штата
      if (!empty($row['city']) || !empty($row['state'])) {
         echo '<tr><td class="label">Location:</td><td>' . $row['city'] . ', ' . $row['state'] . '</td></tr>';
      }

      // отображение картинки
      if (!empty($row['picture'])) {
         echo '<tr><td class="label">Picture:</td><td><img src="' . MM_UPLOADPATH . $row['picture'] .
            '" alt="Profile Picture" /></td></tr>';
      }

      echo '</table>';
      // если данные методом GET не поступили(а они не поступят если юзер не авторизовался) или данные из сессии равны данным из GET  >  вывод ссылки на сценарий Редактирования 
      if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
         echo '<p> Хотите ли вы <a href="editprofile.php"> отредактировать свой профиль </a>?</p>';
         echo '<a href="index.php">  <<  вернуться на Главную </a>';
      }
   } // Конец проверки одной строки пользовательских результатов
   else {
      echo '<p class="error">Возникла проблема с доступом к вашему профилю.</p>';
   }

   mysqli_close($dbc);
   ?>
</body>

</html>