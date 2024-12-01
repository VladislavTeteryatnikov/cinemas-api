<?php

    /**
     * Класс маршрутизатор. Определяет controller и action в зависимости от переданного URL
     */
    class Router
    {
        /**
         * @var array Массив маршрутов
         */
        private $routes;

        /**
         * Конструктор
         */
        public function __construct()
        {
            require_once("configs/routes.php");
            $this->routes = $routes;
        }

        /**
         * Создает экземпляр контроллера и вызывает у него необходимый action в зависимости от переданного URL
         */
        public function run()
        {
            //Получаем адрес страницы, на которую перешел пользователь. Например, /WEB_services/REST/api_v1/recipes/13
            $requestedUrl = $_SERVER['REQUEST_URI'];

            foreach ($this->routes as $controller => $paths) {
                foreach ($paths as $url => $actionWithParameters) {
                    //Если находим совпадение url из routes в url, который ввел пользователь
                      if (preg_match("~$url~", $requestedUrl)) {
                         //Заменяем url на action с параметром (и токен)
                         $actionWithParameters = preg_replace("~$url~", $actionWithParameters, $requestedUrl);
                         $count = 1;
                         //Оставляем только action с параметром (и токен)
                         $actionWithParameters = str_replace(API_ROOT, '', $actionWithParameters, $count);
                         //Удаляем token, чтобы не воспринимался как параметр
                         $actionWithParameters = preg_replace("~\?token=[0-9a-z]*~i", '', $actionWithParameters, $count);
                         //Удаляем гет-параметры
                         $actionWithParameters = preg_replace("~\?key=.*&?~i", '', $actionWithParameters, $count);
                         $actionWithParameters = preg_replace("~application=.*~i", '', $actionWithParameters, $count);
                         $actionWithParameters = preg_replace("~&date=.*~i", '', $actionWithParameters, $count);
                         $actionWithParameters = preg_replace("~&seance=.*~i", '', $actionWithParameters, $count);
                         $actionWithParameters = preg_replace("~&cinemaID=.*~i", '', $actionWithParameters, $count);
                         //Формируем массив. Отдельно action, отдельно параметр
                         $actionWithParametersArray = explode('/', $actionWithParameters);
                         //Передаем action в отдельную переменную и удаляем его и массива
                         $actionWithoutParameters = array_shift($actionWithParametersArray);
                         $requestedController = new $controller();
                         //Проверяем есть ли такой метод у этого контроллера и только тогда его вызываем
                         if (method_exists($requestedController, $actionWithoutParameters)) {
                             //Вызываем action у контроллера, передавая в этот action параметры
                             call_user_func_array(array($requestedController, $actionWithoutParameters), $actionWithParametersArray);
                             exit();
                         }
                         //Завершаем первый foreach, когда получим нужный контроллер и action
                        break 2;
                    }
                }
            }
            header("HTTP/1.1 404 Not found");
        }
    }
