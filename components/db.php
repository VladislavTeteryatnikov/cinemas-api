<?php

    /**
     * Реализация шаблона одиночка (singleton) для подключения к БД
     */
    final class  DB
    {
        /**
         * @var false|mysqli|null Подключение к БД
         */
        private static $connection = null;

        /**
         * Приватный конструктор
         */
        private function __construct()
        {
            require_once ("configs/db.php");
            $connect = mysqli_connect($db['HOST'], $db['USER'], $db['PASSWORD'], $db['DB_NAME']);
            mysqli_set_charset($connect, $db['CHARSET']);
            self::$connection = $connect;
        }

        /**
         * @return false|mysqli|null Подключение к БД
         */
        public static function getConnect()
        {
            if (self::$connection == null) {
                new self();
            }
            return self::$connection;
        }

        /**
         * Убираем клоинрование
         */
        private function __clone()
        {

        }

        /**
         * Убираем сериализацию
         */
        public function __sleep()
        {

        }

        /**
         * Убираем десериализацию
         */
        public function __wakeup()
        {

        }
    }
