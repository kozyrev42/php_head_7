<?php
    // если пользователь вошел в приложение, удаляем куки, выход из приложения
    if (isset($_COOKIE['user_id'])) {
        // установка момента истекания срока действия куки
        // в результате Куки удаляются
        setcookie('user_id','', time()-3600);
        setcookie('username','', time()-3600);

        //function console_log($data){ echo "<script>console.log('php_array: ".json_encode($data)."');</script>";}
        //console_log('123');
    }
    // далее автоматически переходим на главную страницу
    // формируем путь
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
    // передаём путь на выполнение заголовку
    // header ('Location: http://localhost/php_head_7/index.php');
    header ('Location: '. $home_url);
?>