<?php

    /**
     * Модель для работы с таблицей 'status'
     */
    class Status
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
         * @return array Массив со статусами
         */
        public function getAllStatusesId()
        {
            $query = "
                SELECT `ID`
                FROM `status`;
            ";
            $result = mysqli_query($this->connection, $query);
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }