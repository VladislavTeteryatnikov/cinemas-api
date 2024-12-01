<?php

    /**
     * Базовый контроллер
     */
    abstract class BaseController
    {
        /**
         * @var array Ответ на запрос пользователя
         */
        protected $answer;


        abstract function main(string $param);

        /**
         * Выводит ответ на запрос пользователя в json
         */
        private function showResponseBody()
        {
            echo json_encode($this->answer, JSON_UNESCAPED_UNICODE);
        }


        protected function showOk()
        {
            header("HTTP/1.1 200 OK");
            if (!empty($this->answer)) {
                $this->showResponseBody();
            }
            exit();
        }


        protected function showCreated()
        {
            header("HTTP/1.1 201 Created");
            if (!empty($this->answer)) {
                $this->showResponseBody();
            }
            exit();
        }


        protected function showNotFound()
        {
            header("HTTP/1.1 404 Not found");
            if (!empty($this->answer)) {
                $this->showResponseBody();
            }
            exit();
        }

        /**
         *
         */
        protected function showUnauthorized()
        {
            header("HTTP/1.1 401 Unauthorized");
            if (!empty($this->answer)) {
                $this->showResponseBody();
            }
            exit();
        }

        /**
         *
         */
        protected function showBadRequest()
        {
            header("HTTP/1.1 400 Bad request");
            if (!empty($this->answer)) {
                $this->showResponseBody();
            }
            exit();
        }

    }
