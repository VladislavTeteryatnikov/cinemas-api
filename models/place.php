<?php

    /**
     * Модель для работы с табоицей 'ticket'
     */
    class Place
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
         * Получение массива с забронированными или выкупленными местами на сеанс
         * @param int $seanceId ID сеанса
         * @return array Массив с забронированными/выкупленными местами
         */
        public function getReservedPlaces(int $seanceId)
        {
            $query = "
                SELECT `ticket`.`row` AS `row`, `ticket`.`number` AS `number`, `status`.`name` AS `status_name`
                FROM `ticket`
                LEFT JOIN `status` ON `ticket`.`ID_status` = `status`.`ID`
                WHERE `ticket`.`ID_seance` = $seanceId;
            ";
            $result = mysqli_query($this->connection, $query);
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        /**
         * Получение массива со всеми местами на сеанс
         * @param int $seanceId ID сеанса
         * @return array Массив со всеми местами на данный сеанс
         */
        public function getAllPlaces(int $seanceId)
        {
            $query = "
                SELECT `place`.`row` AS `row`, `place`.`number` AS `number` 
                FROM `place`
                LEFT JOIN `seance` ON `seance`.`ID_hall` = `place`.`ID_hall`
                WHERE `seance`.`ID` = $seanceId;
            ";
            $result = mysqli_query($this->connection, $query);
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        /**
         * Покупка/бронирование билета на сеанс
         * @param array $data Информация для покупки билета
         * @return bool|mysqli_result Булево значение успешно выполнен запрос или нет
         */
        public function buyTicket(array $data)
        {
            $query = "
                INSERT INTO `ticket`
                SET `ticket`.`ID_seance` = $data[ID_seance],
                    `ticket`.`ID_status` = $data[ID_status],
                    `ticket`.`row` = $data[row],
                    `ticket`.`number` = $data[number];
            ";
            return mysqli_query($this->connection, $query);
        }
    }
