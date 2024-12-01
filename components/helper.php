<?php

    /**
     * Класс-помощник
     */
    class Helper
    {
        /**
         * Метод для генерации псевдослучайной строки
         * @param int $size Длина генерируемого токена
         * @return string Токен
         */
        public function generateToken(int $size = 32): string
        {
            $symbols = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g'];
            $symbolsLength = count($symbols);
            $token = "";
            for ($i = 0; $i < $size; $i++) {
                $token .= $symbols[rand(0, $symbolsLength - 1)];
            }
            return $token;
        }
    }
