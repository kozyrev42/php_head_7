<?php
	require_once('login.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Mismatch - Просмотр профиля</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h3>Mismatch - Просмотр профиля</h3>

<?php
   require_once('appvars.php');
   require_once('connectvars.php');

   // подключение к базе данных
   $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

   // Получить данные профиля из базы данных
   if (!isset($_GET['user_id'])) {
      $query = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '$user_id'";
   }
   else {
      $query = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_GET['user_id'] . "'";
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
         }
         else if ($row['gender'] == 'F') {
            echo 'Female';
         }
         else {
            echo '?';
         }
         echo '</td></tr>';
      }

      if (!empty($row['birthdate'])) {
         if (!isset($_GET['user_id']) || ($user_id == $_GET['user_id'])) {
         // Показывать пользователю его собственную дату рождения
         echo '<tr><td class="label">Birthdate:</td><td>' . $row['birthdate'] . '</td></tr>';
         }
         else {
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
      // если данные методом GET не поступили > вывод ссылки на сценарий Редактирования 
      if (!isset($_GET['user_id']) || ($user_id == $_GET['user_id'])) {
         echo '<p>Would you like to <a href="editprofile.php">edit your profile</a>?</p>';
      }
   } // Конец проверки одной строки пользовательских результатов
   else {
      echo '<p class="error">Возникла проблема с доступом к вашему профилю.</p>';
   }

   mysqli_close($dbc);
?>
</body> 
</html>