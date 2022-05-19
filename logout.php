<?php
    // если пользователь вошел в приложение, удаляем переменные сессии, для выхода из приложения
    session_start(); // открытие соединения для доступа к переменным
    if (isset($_SESSION['user_id'])) {
        // удаление переменных сессии, путем обнуления массива $_SESSION
        $_SESSION = array();

        //далее удаляем куку хранящую SID сессии
        if (isset($_COOKIE[session_name()])) {
            setcookie (session_name(),'',time()-3600); // путем установки срока действия часом ранее
        }
    }
    // закрытие сессии
    session_destroy();
    
    // удаляем Данные из Куки
    setcookie('user_id', '', time() - 3600);
    setcookie('username', '', time() - 3600);
    // далее автоматически переходим на главную страницу
    // формируем путь
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
    // передаём путь на выполнение заголовку
    // header ('Location: http://localhost/php_head_7/index.php');
    header ('Location: '. $home_url);
?>