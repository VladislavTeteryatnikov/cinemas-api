<?php

    /**
     * Контроллер для получения информации и свободных, забронированных и выкупленных местах на сеанс, а также для офрмления покупки билета на сеанс
     */
    class PlacesController extends BaseController
    {
        /**
         * @var object модель Place
         */
        private $placeModel;

        /**
         * @var object модель Seance
         */
        private $seanceModel;

        /**
         * @var object модель Status
         */
        private $statusModel;

        /**
         * @var object Validate класс для валидации
         */
        private $validate;

        /**
         * @var object модель Application
         */
        private $applicationModel;

        /**
         * Конструктор
         */
        public function __construct()
        {
            $this->placeModel = new Place();
            $this->seanceModel = new Seance();
            $this->validate = new Validate();
            $this->statusModel = new Status();
            $this->applicationModel = new Application();
        }

        /**
         * Вызываем необходимый метод контроллера в зависимости от метода отправки HTTP запроса
         * @param string $param. Параметр, переданный в URL
         */
        public function main(string $param)
        {
            //Для данного URL параметров быть не должно
            if (!empty($param)) {
                $this->answer = ['error' => 'Неверно введен URL'];
                $this->showNotFound();
            }
            $method = $_SERVER['REQUEST_METHOD'];
            if (isset($_GET['token'])) {
                $token = htmlentities($_GET['token']);
                if (!$this->applicationModel->checkToken($token)){
                    $this->showUnauthorized();
                }
            } else {
                $this->showUnauthorized();
            }

            switch ($method){
                case "GET":
                    $this->get();
                    break;
                case "POST":
                    $this->post();
                    break;
                default:
                    $this->showBadRequest();
            }
        }

        /**
         * Получение информации о свободных и занятых местах на определенный сеанс
         */
        private function get()
        {
            if (isset($_GET['seance'])) {
                $seanceId = htmlentities($_GET['seance']);
                if (!is_numeric($seanceId) || $seanceId <= 0) {
                    $this->answer = ['error' => 'Неверно введен id сеанса'];
                    $this->showBadRequest();
                }

                $reservedPlaces = $this->placeModel->getReservedPlaces($seanceId);
                $allPlaces = $this->placeModel->getAllPlaces($seanceId);
                foreach ($allPlaces as $allPlace => $allRowNumber){
                    foreach ($reservedPlaces as $reservedPlace => $reservedRowNumber){
                        if ($allRowNumber['row'] === $reservedRowNumber['row'] && $allRowNumber['number'] === $reservedRowNumber['number']){
                            unset($allPlaces[$allPlace]);
                        }
                    }
                }
                $places = array_merge($allPlaces, $reservedPlaces);
                foreach ($places as &$place) {
                    if (!array_key_exists('status_name', $place)){
                        $place['status_name'] = 'Свободно';
                    }
                }
                unset($place);

                if (!empty($places)){
                    $this->answer = $places;
                    $this->showOk();
                } else {
                    $this->answer = ['error' => 'Сеанс не найден'];
                    $this->showNotFound();
                }
            } else {
                $this->answer = ['error' => 'Необходимо передать параметр seance'];
                $this->showBadRequest();
            }
        }

        /**
         * Покупка билета на определнный сеанс
         */
        private function post()
        {
            $data = json_decode(file_get_contents("php://input"), 1);
            if ($data === null) {
                $this->answer = ['error' => 'Отправленные данные некорректны'];
                $this->showBadRequest();
            }
            //Проверка, что передан валидный массив данных
            $requiredKeys = ['ID_seance', 'ID_status', 'row', 'number'];
            $checkArrayKeys = $this->validate->checkArrayKeys($requiredKeys, $data);
            if (!$checkArrayKeys) {
                $this->answer = ['error' => 'Отправленные данные некорректны'];
                $this->showBadRequest();
            }
            //Проверка, что такой сеанс существует
            $seanceId = $this->seanceModel->getSeanceById($data['ID_seance']);
            if (empty($seanceId)){
                $this->answer = ['error' => 'Сеанс не найден'];
                $this->showNotFound();
            }
            //Проверка, что такие места существуют для этого сеанса и зала
            $allPlaces = $this->placeModel->getAllPlaces($data['ID_seance']);
            $checkPlaceExists = $this->validate->checkPlace($allPlaces, $data['row'], $data['number']);
            if (is_null($checkPlaceExists)) {
                $this->answer = ['error' => 'Место не найдено'];
                $this->showNotFound();
            }
            //Проверка, что места не заняты
            $reservedPlaces = $this->placeModel->getReservedPlaces($data['ID_seance']);
            $checkPlaceTaken = $this->validate->checkPlace($reservedPlaces, $data['row'], $data['number']);
            if (!is_null($checkPlaceTaken)) {
                $this->answer = ['error' => 'Место куплено или забронировано'];
                $this->showBadRequest();
            }
            //Проверка, что передаваемый статус билета валидный
            $allStatusesId = $this->statusModel->getAllStatusesId();
            $columnName = 'ID';
            $checkStatusExists = $this->validate->checkIdExists($allStatusesId, $data['ID_status'], $columnName);
            if (is_null($checkStatusExists)){
                $this->answer = ['error' => 'ID_status не существует'];
                $this->showNotFound();
            }

            //Если все проверки пройдены, то вносим в базу
            if ($this->placeModel->buyTicket($data)){
                $this->answer = 'Created';
                $this->showCreated();
            } else{
                $this->answer = ['error' => 'Не удалось приобрести билет'];
                $this->showBadRequest();
            }
        }
    }
