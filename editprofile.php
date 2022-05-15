<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Mismatch - редактирование профиля </title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h3>Mismatch - редактирование профиля </h3>

<?php
   require_once('appvars.php');
   require_once('connectvars.php');

   // подключение к базе
   $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

   // следующие Условие выполняется Асинхронно с загрузкой страници
   // если данные пришли по средствам $_POST из формы в этом же скрипте, в результате события 'submit'
   if (isset($_POST['submit'])) {
      // берем данные из массива $_POST
      // mysqli_real_escape_string () - экранирует опасные символы, то есть преобразует их в такой вид, в котором они больше не рассмотриваются SQL-интерпретатором
      // trim () - удаляет лишнии пробелы
      $first_name = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
      $last_name = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
      $gender = mysqli_real_escape_string($dbc, trim($_POST['gender']));
      $birthdate = mysqli_real_escape_string($dbc, trim($_POST['birthdate']));
      $city = mysqli_real_escape_string($dbc, trim($_POST['city']));
      $state = mysqli_real_escape_string($dbc, trim($_POST['state']));
      $old_picture = mysqli_real_escape_string($dbc, trim($_POST['old_picture']));
      $new_picture = mysqli_real_escape_string($dbc, trim($_FILES['new_picture']['name']));
      $new_picture_type = $_FILES['new_picture']['type'];
      $new_picture_size = $_FILES['new_picture']['size']; 
      list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);
      $error = false;


      // если в переменную поступило значение имени файла
      if (!empty($new_picture)) {
         // проверка на: тип, вес, размер
         if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
         ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) &&
         ($new_picture_width <= MM_MAXIMGWIDTH) && ($new_picture_height <= MM_MAXIMGHEIGHT)) {
         
            if ($_FILES['file']['error'] == 0) {
               // перенос файла из временной папки в постоянную
               // basename() — Возвращает последний компонент имени из указанного пути
               $target = MM_UPLOADPATH . basename($new_picture);
               if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) {
                  // удаление старой фото, если старая не ровна новой
                  if (!empty($old_picture) && ($old_picture != $new_picture)) {
                  @unlink(MM_UPLOADPATH . $old_picture);
                  }
               }
               else {
                  // Не удалось переместить новый файл изображения, поэтому удалите временный файл и установите флаг ошибки.
                  @unlink($_FILES['new_picture']['tmp_name']);
                  $error = true;
                  echo '<p class="error">Извините, возникла проблема с загрузкой вашего изображения.</p>';
               }
            }
         }
         else {
            // Новый файл изображения не прошел Проверку по условиям, поэтому удаляется временный файл, установлен флаг ошибки.
            @unlink($_FILES['new_picture']['tmp_name']);
            $error = true;
            echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
               ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size.</p>';
         }
      }


      // Update the profile data in the database
      // Обновите данные профиля в базе данных
      if (!$error) {
         // проверка на пустату переменных
         if (!empty($first_name) && !empty($last_name) && !empty($gender) && !empty($birthdate) && !empty($city) && !empty($state)) {
         
            // обновляем если есть новое изображение
            if (!empty($new_picture)) {
               $query = "UPDATE mismatch_user SET first_name = '$first_name', last_name = '$last_name', gender = '$gender', " .
                  " birthdate = '$birthdate', city = '$city', state = '$state', picture = '$new_picture' WHERE user_id = '$user_id'";
            }
            // иначе обновляем просто данные
            else {
               $query = "UPDATE mismatch_user SET first_name = '$first_name', last_name = '$last_name', gender = '$gender', " .
                  " birthdate = '$birthdate', city = '$city', state = '$state' WHERE user_id = '$user_id'";
            }
            mysqli_query($dbc, $query);

            // Подтвердите успех с пользователем
            echo '<p>Ваш профиль был успешно обновлен. Вы не хотите
            <a href="viewprofile.php"> просмотреть свой профиль </a>?</p>';

            mysqli_close($dbc);
            exit();
         }
         else {
         // если переменные содержат пустоту
         echo '<p class="error">Вы должны ввести все данные профиля (картинка необязательна).</p>';
         }
      }
   } 
   // если данные НЕ пришли по средствам $_POST из формы в этом же скрипте
   // условие выполняется при первой загрузки страницы
   else {
      // просто Запрос на данные пользователя
      // назначаем переменным текущие данные
      $query = "SELECT first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '$user_id'";
      $data = mysqli_query($dbc, $query);
      $row = mysqli_fetch_array($data);

      // назначаем переменным текущие данные
      if ($row != NULL) {
         $first_name = $row['first_name'];
         $last_name = $row['last_name'];
         $gender = $row['gender'];
         $birthdate = $row['birthdate'];
         $city = $row['city'];
         $state = $row['state'];
         $old_picture = $row['picture'];
      }
      else {
         echo '<p class="error"> Возникла проблема с доступом к вашему профилю! </p>';
      }
   }

   mysqli_close($dbc);
?>

   <!-- вывод формы для редактирования профиля -->
   <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE; ?>" />
      <fieldset>
         <legend> Персональная информация </legend>
         <label for="firstname">First name:</label>
         <input type="text" id="firstname" name="firstname" value="<?php if (!empty($first_name)) echo $first_name; ?>" /><br />
         <label for="lastname">Last name:</label>
         <input type="text" id="lastname" name="lastname" value="<?php if (!empty($last_name)) echo $last_name; ?>" /><br />
         <label for="gender">Gender:</label>
         <select id="gender" name="gender">
         <option value="M" <?php if (!empty($gender) && $gender == 'M') echo 'selected = "selected"'; ?>>Мужчина</option>
         <option value="F" <?php if (!empty($gender) && $gender == 'F') echo 'selected = "selected"'; ?>>Женщина</option>
         </select><br />
         <label for="birthdate">Birthdate:</label>
         <input type="text" id="birthdate" name="birthdate" value="<?php if (!empty($birthdate)) echo $birthdate; else echo 'YYYY-MM-DD'; ?>" /><br />
         <label for="city">City:</label>
         <input type="text" id="city" name="city" value="<?php if (!empty($city)) echo $city; ?>" /><br />
         <label for="state">State:</label>
         <input type="text" id="state" name="state" value="<?php if (!empty($state)) echo $state; ?>" /><br />
         <!-- если старое изображение есть, то вывод наименования -->
         <input type="hidden" name="old_picture" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" />
         <!-- изображение -->
         <label for="new_picture">Изображение:</label>
         <input type="file" id="new_picture" name="new_picture" />
         <!-- вывод графического изображения -->
         <?php if (!empty($old_picture)) {
         echo '<img class="profile" src="' . MM_UPLOADPATH . $old_picture . '" alt="Profile Picture" />';
         } ?>
      </fieldset>
      <input type="submit" value="Save Profile" name="submit" />
   </form>

</body> 
</html>