<?php

    /**
     * Модель для работы с таблицей 'seance'
     */
    class Seance
    {
        /**
         * @var false|mysqli|null
         */
        private $connection;

        /**
         *
         */
        public function __construct()
        {
            $this->connection = DB::getConnect();
        }

        /**
         * Получение всех сеансов
         * @return array Двумерный массив с информацией по всем сеансам
         */
        public function getAllSeances()
        {
            $query = "
                SELECT `seance`.`ID` AS `seance_id`, `cinema`.`name` AS `cinema_name`, `hall`.`name` AS `hall_name`, `movies`.`name` AS `movie_name`, `movies`.`desc` AS `movie_description`, `movies`.`census` AS `movie_census`, `genre`.`name` AS `genre_name`, CONCAT(`directed`.`first_name`,' ', `directed`.`last_name`) AS `directed`, `seance`.`datetime` AS `seance_datetime`, `seance`.`price` AS `seance_price`, GROUP_CONCAT(CONCAT(`actor`.`first_name`,' ', `actor`.`last_name`)  SEPARATOR ', ') AS `actors`
                FROM `seance`
                LEFT JOIN `hall` ON `seance`.`ID_hall` = `hall`.`ID` 
                LEFT JOIN `cinema` ON `hall`.`ID_cinema` = `cinema`.`ID`
                LEFT JOIN `movies` ON `seance`.`ID_movie` = `movies`.`ID`
                LEFT JOIN `genre` ON `movies`.`ID_genre` = `genre`.`ID`
                LEFT JOIN `directed` ON `movies`.`ID_directed` = `directed`.`ID`
                LEFT JOIN `actor_list` ON `movies`.`ID` = `actor_list`.`ID_movies`
                LEFT JOIN `actor` ON `actor_list`.`ID_actor` = `actor`.`ID`
                GROUP BY `seance`.`ID`;
            ";
            $result = mysqli_query($this->connection, $query);
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        /**
         * Получение информации о сеансах на конретную дату
         * @param string $date Дата
         * @return array Массив с сеансами
         */
        public function getSeancesByDate(string $date)
        {
            $query = "
                SELECT `seance`.`ID` AS `seance_id`, `cinema`.`name` AS `cinema_name`, `hall`.`name` AS `hall_name`, `movies`.`name` AS `movie_name`, `movies`.`desc` AS `movie_description`, `movies`.`census` AS `movie_census`, `genre`.`name` AS `genre_name`, CONCAT(`directed`.`first_name`,' ', `directed`.`last_name`) AS `directed`, `seance`.`datetime` AS `seance_datetime`, `seance`.`price` AS `seance_price`, GROUP_CONCAT(CONCAT(`actor`.`first_name`,' ', `actor`.`last_name`)  SEPARATOR ', ') AS `actors`
                FROM `seance`
                LEFT JOIN `hall` ON `seance`.`ID_hall` = `hall`.`ID` 
                LEFT JOIN `cinema` ON `hall`.`ID_cinema` = `cinema`.`ID`
                LEFT JOIN `movies` ON `seance`.`ID_movie` = `movies`.`ID`
                LEFT JOIN `genre` ON `movies`.`ID_genre` = `genre`.`ID`
                LEFT JOIN `directed` ON `movies`.`ID_directed` = `directed`.`ID`
                LEFT JOIN `actor_list` ON `movies`.`ID` = `actor_list`.`ID_movies`
                LEFT JOIN `actor` ON `actor_list`.`ID_actor` = `actor`.`ID`
                WHERE `seance`.`datetime` LIKE '$date%'
                GROUP BY `seance`.`ID`;
            ";
            $result = mysqli_query($this->connection, $query);
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        /**
         * Получение информации о сеансе по id
         * @param int $id ID сеанса
         * @return array|false|null Массив с информацией о сеансе
         */
        public function getSeanceById(int $id)
        {
            $query = "
                SELECT `seance`.`ID` AS `seance_id`, `cinema`.`name` AS `cinema_name`, `hall`.`name` AS `hall_name`, `movies`.`name` AS `movie_name`, `movies`.`desc` AS `movie_description`, `movies`.`census` AS `movie_census`, `genre`.`name` AS `genre_name`, CONCAT(`directed`.`first_name`,' ', `directed`.`last_name`) AS `directed`, `seance`.`datetime` AS `seance_datetime`, `seance`.`price` AS `seance_price`, GROUP_CONCAT(CONCAT(`actor`.`first_name`,' ', `actor`.`last_name`)  SEPARATOR ', ') AS `actors`
                FROM `seance`
                LEFT JOIN `hall` ON `seance`.`ID_hall` = `hall`.`ID` 
                LEFT JOIN `cinema` ON `hall`.`ID_cinema` = `cinema`.`ID`
                LEFT JOIN `movies` ON `seance`.`ID_movie` = `movies`.`ID`
                LEFT JOIN `genre` ON `movies`.`ID_genre` = `genre`.`ID`
                LEFT JOIN `directed` ON `movies`.`ID_directed` = `directed`.`ID`
                LEFT JOIN `actor_list` ON `movies`.`ID` = `actor_list`.`ID_movies`
                LEFT JOIN `actor` ON `actor_list`.`ID_actor` = `actor`.`ID`
                WHERE `seance`.`ID` = $id
                GROUP BY `seance`.`ID`;
            ";
            $result = mysqli_query($this->connection, $query);
            return mysqli_fetch_assoc($result);
        }
    }
