<?php

    /**
     * Модель для работы с таблицей 'hall'
     */
    class Hall
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
         * Получение информации о зале кинотетра по id
         * @param int $id ID зала
         * @return array|false|null Массив с информацией про зал
         */
        public function getHall(int $id)
        {
            $query = "
                SELECT `hall`.`ID` as `hall_id`, `cinema`.`name` AS `cinema_name`, `hall`.`name` AS `hall_name`
                FROM `hall`
                LEFT JOIN `cinema` ON `hall`.`ID_cinema` = `cinema`.`ID`
                WHERE `hall`.`ID` = $id;
            ";
            $result = mysqli_query($this->connection, $query);
            return mysqli_fetch_assoc($result);
        }

        /**
         * Получение информации про все залы
         * @return array Двумерный массив с информацией про каждый зал
         */
        public function getAllHall()
        {
            $query = "
                SELECT `hall`.`ID` as `hall_id`, `cinema`.`name` AS `cinema_name`, `hall`.`name` AS `hall_name`
                FROM `hall`
                LEFT JOIN `cinema` ON `hall`.`ID_cinema` = `cinema`.`ID`;
            ";
            $result = mysqli_query($this->connection, $query);
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        /**
         * Получение информации по залам конкретного кинотеатра
         * @param int $cinemaId ID киноетра
         * @return array Двумерный массив с информацией про каждый зал кинотеатра
         */
        public function getHallsByCinemaId(int $cinemaId)
        {
            $query = "
                SELECT *
                FROM `hall`
                WHERE `ID_cinema` = $cinemaId;
            ";
            $result = mysqli_query($this->connection, $query);
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }