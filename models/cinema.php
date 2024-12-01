<?php

    /**
     * Модель для работы с таблицей 'cinema'
     */
    class Cinema
    {
        /**
         * @var false|mysqli|null Подключение к БД
         */
        private $connection;

        /**
         * Конструктор
         */
        public function __construct()
        {
            $this->connection = DB::getConnect();
        }

        /**
         * Получение информации про конкретный кинотеатр по id
         * @param int $id ID кинотеатра
         * @return array|false|null Массив с информацией про кинотеатр
         */
        public function getCinema(int $id)
        {
            $query = "
                SELECT *
                FROM `cinema`
                WHERE `ID` = $id; 
            ";
            $result = mysqli_query($this->connection, $query);
            return mysqli_fetch_assoc($result);
        }

        /**
         * Получение информации про все кинотеатры
         * @return array Двумерный массив с информацией про каждый кинотеатр
         */
        public function getAllCinema()
        {
            $query = "
                SELECT *
                FROM `cinema`
            ";
            $result = mysqli_query($this->connection, $query);
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }
