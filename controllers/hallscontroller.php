<?php

    /**
     * Контроллер для получения информации о залах кинотеатров
     */
    class HallsController extends BaseController
    {
        /**
         * @var object модель Cinema
         */
        private $cinemaModel;

        /**
         * @var object модель Application
         */
        private $applicationModel;

        /**
         * @var object модель Hall
         */
        private $hallModel;

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
            $this->hallModel = new Hall();
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
         * Получение информации обо всех залах кинотеатров, либо конкретном зале. Информация о залах конкретного кинотеатра
         * @param string $id ID зала
         */
        private function get($id)
        {
            $halls = $this->hallModel->getAllHall();
            if ($id || $id === '0') {
                if (!is_numeric($id)) {
                    $this->answer = ['error' => 'Неверно введен id'];
                    $this->showBadRequest();
                }
                $columnName = 'hall_id';
                $checkHallId = $this->validate->checkIdExists($halls, $id, $columnName);
                if (is_null($checkHallId)){
                    $this->answer = ['error' => 'Id не существует'];
                    $this->showNotFound();
                }
                $hall = $this->hallModel->getHall($id);
                $this->answer = $hall;
            } else {
                if (isset($_GET['cinemaID'])){
                    $cinemaID = htmlentities($_GET['cinemaID']);
                    if (!is_numeric($cinemaID)) {
                        $this->answer = ['error' => 'Неверно введен id'];
                        $this->showBadRequest();
                    }
                    $cinemas = $this->cinemaModel->getAllCinema();
                    $columnName = 'ID';
                    $checkCinemaId = $this->validate->checkIdExists($cinemas, $cinemaID, $columnName);
                    if (is_null($checkCinemaId)){
                        $this->answer = ['error' => 'Id не существует'];
                        $this->showNotFound();
                    }
                    $halls = $this->hallModel->getHallsByCinemaId($cinemaID);
                    $this->answer = $halls;

                } else {
                    $halls = $this->hallModel->getAllHall();
                    $this->answer = $halls;
                }

            }
            $this->showOk();
        }
    }
