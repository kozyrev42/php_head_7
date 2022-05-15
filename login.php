<?php
   require_once ('connectvars.php');

   if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
        // имя пользователя и/или его пароль не были введены
        // поэтому отправляются заголовки аутентификации
        header ('HTTP/1.1 401 Unauthorized');
        header ('WWW-Authenticate:Basic realm="Mismatch"'); // благодаря Basic realm - браузер запоминает правильно-введенный логин и пароль
        // если пользователь кликнет "Отмена" > функция exit() выведит нужное сообщение
        // если аутентификация успешная > функция exit() Не вызывается
        exit ('<meta charset="utf-8"><h2> Несоответствия </h2> <h5>необходимо ввести имя и пароль для входа</h5>');
   }

   // подключение к базе
   $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 

   // назначение переменным, введенных данных пользователем для аутентификации
   $user_username = mysqli_real_escape_string($dbc, trim($_SERVER['PHP_AUTH_USER']));
   $user_password = mysqli_real_escape_string($dbc, trim($_SERVER['PHP_AUTH_PW']));

   // запрос в базу, на имя пользователя и пароль,
   // выборка происходит при условии, если данные в базе совпадают с данными, введенными пользователем
   $query = "SELECT user_id, username FROM mismatch_user WHERE username='$user_username' AND password=SHA('$user_password')"; 
   $data = mysqli_query($dbc, $query);

   // mysqli_num_rows() - функция Возвращает число рядов в результирующей выборке.
   if (mysqli_num_rows($data) == 1) {
      // Пользовательская строка была найдена, поэтому отображаются пользовательские данные
      // процедура входа успешна
      // присваиваем переменным id и имя Пользователя
      $row = mysqli_fetch_array($data);
      $user_id = $row['user_id'];
      $username = $row['username'];
   }
   else {
      // если имя и/или пароль введены неверно
      // заголовки на аутентификацию отправляются сново
      header ('HTTP/1.1 401 Unauthorized');
      header ('WWW-Authenticate:Basic realm="Mismatch"');
      exit ('<meta charset="utf-8"><h2> Несоответствия </h2> <h5>необходимо ввести имя и пароль для входа</h5>');
   }

   // если логин и пароль верны, вывод о успехе
   echo ('<p class="login">Вы вошли как' . $username . '</p>');
?>