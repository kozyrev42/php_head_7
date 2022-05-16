<?php
   // ?
   require_once('connectvars.php');

   // обнуление сообщения об ошибки
   // ошибка - выводится когда это нужно
   $error_msg='';

   // если юзер не вошел в приложение, выполняется попытка войти
   // если $_COOKIE['user_id'] - содержит данные - значит вход выполнен
   if (!isset($_COOKIE['user_id'])) {

      // Асинхронная проверка $_POST
      // подключение к базе
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      // назначение переменным, введенных данных пользователем при аутентификации
      // $_POST['username'] - данные из этого же скрипта 
      $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      // проверка на ввод 2 полей
      if (!empty($user_username) && !empty($user_password)) {
         // поиск в базе введенных Логина и Пароля
         // выборка происходит при условии, если данные в базе совпадают с данными, введенными пользователем
         $query = "SELECT user_id, username FROM mismatch_user WHERE username='$user_username' AND password=SHA('$user_password')"; 
         $data = mysqli_query($dbc, $query);

         // mysqli_num_rows() - функция Возвращает число рядов в результирующей выборке.
         if (mysqli_num_rows($data) == 1) {
            // Пользовательская строка была найдена
            // процедура входа успешна
            // Сохраняем в куки ID и ЛОГИН пользователя 
            $row = mysqli_fetch_array($data);
            setcookie('user_id', $row['user_id'], time()+(60*60*24*30)); // срок действия 30 дней
            setcookie('username', $row['username'], time()+(60*60*24*30)); // срок действия 30 дней

            // далее автоматически переходим на главную страницу
            // формируем путь
            $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
            // передаём путь на выполнение заголовку
            header ('Location:' . $home_url);
         }
         else {
            // логин или пароль введены неверно, составляется сообщение об ошибке
            $error_msg = 'введите правильно Логин и Пароль';
         }
      }
      else {
         // логин или пароль не введены, составляется сообщение об ошибке
         $error_msg = 'введите логин и пароль для входа';
      }
   }
?>
<html>
   <head>
      <title> Вход в приложение </title>
      <link rel="stylesheet" type="text/css" href="style.css"/>
   </head>
   <body>
      <h3>Несоответствия. Вход в приложение.</h3>

<?php 
   // если куки не содержит данных, выводим сообщение об ошибке
   // и  форму для входа
   // в противном случае подтверждение входа
   if (empty($_COOKIE['user_id'])) {
      echo '<p class="error">' . $error_msg . '</p>';
?>
   <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
      <fieldset>
         <legend>Вход в приложение</legend>
         <label for="username">Имя пользователя</label>
         <input type="text" name="username" value="<?php if(!empty($user_name)) echo $user_name?>"/> <br/>

         <label for="password">Пароль</label>
         <input type="password" name="username" /> <br/>
      </fieldset>
      <input type="submit" value="Войти" name="submit"/>
   </form>
<?php
   } else {
      // подтверждение успешного входа в приложение
      echo '<p class="login"> Вы вошли как' . $_COOKIE['username'] . '!!!</p>';
   }
?>

   </body>
</html>