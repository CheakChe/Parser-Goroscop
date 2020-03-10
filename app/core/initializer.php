<?php
spl_autoload_register(static function ($item) {
    if (file_exists('app/controllers/' . ucfirst($item) . '.php')) {
        include_once 'app/controllers/' . ucfirst($item) . '.php';
    } elseif (file_exists("app/core/$item.php")) {
        include_once "app/core/$item.php";
    } elseif (file_exists("app/models/$item.php")) {
        include_once "app/models/$item.php";
    } else {
        Log::writeLog("Файл $item не был найден.");
        header('Location: /error');
    }
});
session_start();
(new Router())->init();
session_write_close();

