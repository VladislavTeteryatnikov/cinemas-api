<?php

    /**
     * Контроллер для получения информации о кинотеатрах
     */
    class CinemasController extends BaseController
    {
        /**
         * @var object модель Application
         */
        private $applicationModel;

        /**
         * @var object модель Cinema
         */
        private $cinemaModel;

        /**
         * @var object Validate класс для валидации
         */
        private $validate;

        /**
         * Конструктор
         */
        public function __construct()
        {
            $this->applicationModel = new Application();
            $this->cinemaModel = new Cinema();
            $this->validate = new Validate();
        }

        /**
         * Вызываем необходимый метод контроллера в зависимости от метода отправки HTTP запроса
         * @param string $param. Параметр, переданный в URL
         */
        public function main(string $param)
        {
            $method = $_SERVER['REQUEST_METHOD'];
            if (isset($_GET['token'])) {
                $token = htmlentities($_GET['token']);
                if (!$this->applicationModel->checkToken($token)){
                    $this->showUnauthorized();
                }
            } else {
                $this->showUnauthorized();
            }
            switch ($method) {
                case 'GET':
                    $this->get($param);
                    break;
                default:
                    $this->showBadRequest();
            }
        }

        /**
         * Получение информации обо всех кинотеатрах, либо конкретном кинотеатре
         * @param string $id ID кинотеатра
         */
        public function get($id)
        {
            $cinemas = $this->cinemaModel->getAllCinema();
            if ($id || $id === '0') {
                if (!is_numeric($id)) {
                    $this->answer = ['error' => 'Неверно введен id'];
                    $this->showBadRequest();
                }
                $columnName = 'ID';
                $checkCinemaId = $this->validate->checkIdExists($cinemas, $id, $columnName);
                if (is_null($checkCinemaId)){
                    $this->answer = ['error' => 'Id не существует'];
                    $this->showNotFound();
                }
                $cinema = $this->cinemaModel->getCinema($id);
                $this->answer = $cinema;
            } else {
                $this->answer = $cinemas;
            }
            $this->showOk();
        }
    }