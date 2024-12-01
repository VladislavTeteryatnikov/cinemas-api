<?php

    /**
     * Контроллер для авторизации пользователя для использования api
     */
    class AuthController extends BaseController
    {
        /**
         * @var object Модель Application
         */
        private $applicationModel;

        /**
         * Конструктор
         */
        public function __construct()
        {
            $this->applicationModel = new Application();
        }

        /**
         * Вызываем необходимый метод контроллера в зависимости от метода отправки HTTP запроса
         * @param string $param. Параметр, переданный в URL
         */
        public function main(string $param)
        {
            if (!empty($param)) {
                $this->answer = ['error' => 'Неверно введен URL'];
                $this->showNotFound();
            }
            $method = $_SERVER['REQUEST_METHOD'];
            switch ($method){
                case "GET":
                    $this->get();
                    break;
                default:
                    $this->showBadRequest();
            }
        }

        /**
         * Генерация уникального токена и авторизация пользователя
         */
        protected function get()
        {
            if (isset($_GET['key']) && isset($_GET['application'])){
                $key = htmlentities($_GET['key']);
                $appId = htmlentities($_GET['application']);

                //Проверка, что key и id валидны (числа)
                if (!is_numeric($key) || !is_numeric($appId)) {
                    $this->showUnauthorized();
                }

                //Генерируем токен
                $token = $this->applicationModel->getToken($key, $appId);
                if ($token !== ''){
                    $this->answer = ["token" => $token];
                    $this->showOk();
                } else{
                    $this->showUnauthorized();
                }
            } else {
                $this->showUnauthorized();
            }
        }
    }
