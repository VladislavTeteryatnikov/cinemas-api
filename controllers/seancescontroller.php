<?php

    /**
     * Контроллер для получения информации о сеансах
     */
    class SeancesController extends BaseController
    {
        /**
         * @var object модель Application
         */
        private $applicationModel;

        /**
         * @var object модель Seance
         */
        private $seanceModel;

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
            $this->seanceModel = new Seance();
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
         * Получение информации обо всех сеансах, либо конкретном кинотеатре. Информация о сеансах на конкретную дату
         * @param string $id ID сеанса
         */
        public function get($id)
        {
            $seances = $this->seanceModel->getAllSeances();
            if ($id || $id === '0') {
                if (!is_numeric($id)) {
                    $this->answer = ['error' => 'Неверно введен id'];
                    $this->showBadRequest();
                }
                $columnName = 'seance_id';
                $checkHallId = $this->validate->checkIdExists($seances, $id, $columnName);
                if (is_null($checkHallId)){
                    $this->answer = ['error' => 'Id не существует'];
                    $this->showNotFound();
                }
                $seance = $this->seanceModel->getSeanceById($id);
                $this->answer = $seance;
                $this->showOk();
            } else {
                if (isset($_GET['date'])){
                    $date = htmlentities($_GET['date']);
                    $checkDate = $this->validate->checkDate($date);
                    if (!$checkDate) {
                        $this->answer = ['error' => 'Неверный формат даты'];
                        $this->showBadRequest();
                    }
                    $seancesByDate = $this->seanceModel->getSeancesByDate($date);
                    if ($seancesByDate) {
                        $this->answer = $seancesByDate;
                    } else {
                        $this->answer = ['error' => 'Не найдено сеансов на данную дату'];
                    }
                } else {
                    $this->answer = $seances;
                }
                $this->showOk();
            }

        }
    }
