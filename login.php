<?php
   // сценарий входа
   session_start(); // открытие соединения для доступа к переменным
   //require_once('connectvars.php');

   // обнуление сообщения об ошибки
   // ошибка - выводится когда это нужно
   $error_msg='';
   //$link_home='';

   // если $_SESSION['user_id'] - содержит данные - значит вход выполнен
   // если юзер не вошел в приложение, выполняется попытка войти
   if (!isset($_SESSION['user_id'])) {

      if (isset($_POST['submit'])) {
         // Асинхронная проверка $_POST
         // подключение к базе
         define('DB_HOST', '127.0.0.1');    // адрес сервера БД
         define('DB_USER', 'root');         // пользователь БД
         define('DB_PASSWORD', '');         // пароль
         define('DB_NAME', 'my_php');       // имя базы данных на Хосте
         $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

         // назначение переменным, введенных данных пользователем при аутентификации
         // $_POST['username'] - данные из этого же скрипта 
         $user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
         $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

         // проверка на ввод Логина и Пароля
         if (!empty($user_username) && !empty($user_password)) {
            // поиск в базе введенных Логина и Пароля
            // выборка происходит при условии, если данные в базе совпадают с данными, введенными пользователем
            $query = "SELECT user_id, username FROM mismatch_user WHERE username='$user_username' AND password=SHA('$user_password')"; 
            $data = mysqli_query($dbc, $query);

            // mysqli_num_rows() - функция Возвращает число рядов в результирующей выборке.
            if (mysqli_num_rows($data) == 1) {
               // Пользовательская строка была найдена
               // процедура входа успешна
               // Сохраняем в переменные сессии ID и ЛОГИН пользователя 
               $row = mysqli_fetch_array($data);
               $_SESSION['user_id']  = $row['user_id'];
               $_SESSION['username'] = $row['username'];
               // плюс сохраняем данные авторизации в куки
               setcookie ('user_id', $row['user_id'], time()+(60*60*24*30)); // срок действия 30 дней
               setcookie ('username', $row['username'], time()+(60*60*24*30)); // срок действия 30 дней

               // далее автоматически переходим на главную страницу
               // формируем путь
               $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
               // передаём путь на выполнение заголовку
               // header ('Location:' . $home_url);
               header ('Location: http://localhost/php_head_7/index.php');
            }
            else {
               // логин или пароль введены неверно, составляется сообщение об ошибке
               $error_msg = 'введите правильно Логин и Пароль';
               $link_home = '<a href="index.php">  <<  вернуться на Главную </a>';
            }
         }
         else {
            // логин или пароль не введены, составляется сообщение об ошибке
            $error_msg = 'введите логин и пароль для входа';
            $link_home = '<a href="index.php">  <<  вернуться на Главную </a>';
         }
      }
   }
?>

<html>
<head>
    <meta charset="utf-8">
    <title> Вход в приложение </title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
    <h3>Несоответствия. Вход в приложение.</h3>

    <?php 
   // если данных из Сессии нет, выводим сообщение об ошибке
   // и  форму для входа
   // иначе подтверждение входа
   if (empty($_SESSION['user_id'])) {
      echo '<p class="error">' . $error_msg . ' <br/>';
      echo $link_home . '<br/>';

?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <fieldset>
            <legend>Вход в приложение</legend>
            <label for="username">Имя пользователя</label>
            <input type="text" name="username" value="<?php if(!empty($user_name)) echo $user_name?>" /> <br />

            <label for="password">Пароль</label>
            <input type="password" name="password" /> <br />
        </fieldset>
        <input type="submit" value="Войти" name="submit" />
    </form>
    <?php
   } else {
      // подтверждение успешного входа в приложение
      echo '<p class="login"> Вы вошли как' . $_SESSION['username'] . '!!!</p>';
   }
?>

</body>

</html>