<?php

    /**
     * Модель для работы с таблицей 'applications'
     */
    class Application
    {
        /**
         * @var false|mysqli|null Подключение к БД
         */
        private $connection;

        /**
         * @var object Helper класс-помощник
         */
        private $helper;

        /**
         * Конструктор
         */
        public function __construct()
        {
            $this->helper = new Helper();
            $this->connection = DB::getConnect();
        }

        /**
         * Генерация псевдослучайно строки (токена)
         * @param int $key
         * @param string $appId
         * @return string Токена
         */
        public function getToken(int $key, string $appId)
        {
            $query = "
                SELECT COUNT(*) AS `count`
                FROM `applications`
                WHERE `application_id` = $appId
                    AND `application_key` = '$key';
            ";
            $result = mysqli_query($this->connection, $query);
            if (mysqli_fetch_assoc($result)['count'] == 1){
                $token = $this->helper->generateToken();
                $query = "
                    UPDATE `applications`
                    SET `application_token` = '$token'
                    WHERE `application_id` = $appId;
                ";
                mysqli_query($this->connection, $query);
                return $token;
            } else{
                return '';
            }
        }

        /**
         * Проверка существования токена
         * @param string $token Токен
         * @return bool true, если существует
         */
        public function checkToken(string $token)
        {
            $query = "
                SELECT COUNT(*) AS `count`
                FROM `applications`
                WHERE `application_token` = '$token';
            ";
           $result = mysqli_query($this->connection, $query);
           return mysqli_fetch_assoc($result)['count'] == 1;
        }
    }