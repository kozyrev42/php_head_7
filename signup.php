<!-- сценарий регистрации пользователей -->
<?php
   require_once('appvars.php');
   require_once('connectvars.php');

   // подключение к базе 
   $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

   // условие работает Асинхронно
   // для записи в базу регестрационных данных
   if (isset($_POST['submit'])) {
      // извлечение данных из $_POST
      $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
      $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

      // проверка на пустоту и равенстава переменных
      if (!empty($username) && !empty($password1) && !empty($password2) && ($password1==$password2) ) {
         // проверка на уникальность имени нового пользователя
         $query = "SELECT * FROM mismatch_user WHERE username='$username'";
         $data = mysqli_query($dbc, $query);
         
         if ($row = mysqli_fetch_array($data) == 0) {
            // если ответ пришел пустым
            // значит введенного имени нет в базе, делаем новую запись
            $query = "INSERT INTO `mismatch_user` (username, password, join_date) VALUES ('$username', SHA('$password1'), NOW())";
            $data = mysqli_query($dbc, $query);

            // выводим подтверждение пользователю
            echo '<p> Учетная запись создана. Вы можете <a href="editprofile.php"> отредактировать </a> профиль </p>';
            mysqli_close($dbc);
            
            // прерывание исполнения скрипта
            exit();
         }
         else {
            // иначе ответ содержит данные
            // такое имя-пользователя содержится в базе
            echo '<p> Учетная запись с таким именем уже существует! Введите другое имя. </p>';
            // обнуляем значение переменной
            $username="";
         }
      } else {
         echo '<p> Необходимо заполнить все поля </p>';
      }
   }
   // первое условие не выполнилось, закрываем соединение
   mysqli_close($dbc);
?>

<h5> Введите имя и пароль для создания учетной записи </h5>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
    <fieldset>
        <legend>Входные данные</legend>
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo $username?>" /> <br />

        <label for="username">Пароль:</label>
        <input type="password" id="password1" name="password1" /> <br />

        <label for="username">Повторите пароль:</label>
        <input type="password" id="password2" name="password2" /> <br />
    </fieldset>
    <input type="submit" value="Создать" name="submit" />
</form>