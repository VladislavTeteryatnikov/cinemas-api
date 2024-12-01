<?php

    /**
     * Валидация данных
     */
    class Validate
    {
        /**
         * Проверка, что передаваемые места существуют или не заняты и не забронированы
         * @param array $Places Массив с местами (№ ряда и № места)
         * @param string $row № ряда
         * @param string $num № места
         * @return bool|null !null, если место занято
         */
        public function checkPlace(array $Places, string $row, string $num)
        {
            foreach ($Places as $Place) {
                if ($row === $Place['row'] && $num === $Place['number']) {
                    return !null;
                }
            }
            return null;
        }


        /**
         * Проверка, что передаваемый ID существует
         * @param array $allIds Многомерный массив с сущностями
         * @param int $id Проверяемый id
         * @param string $columnName Колонка, по которой проверяем (как правило ID)
         * @return bool|null !null, если проверяемый id существует
         */
        public function checkIdExists(array $allIds, int $id, string $columnName)
        {
            foreach ($allIds as $allId) {
                if ($allId[$columnName] == $id) {
                    return !null;
                }
            }
            return null;
        }


        /**
         * Проверка, что дата валидна и соответствует формату ГГГГ-ММ-ДД
         * @param string $date Дата
         * @return bool true, если дата валидна
         */
        public function checkDate(string $date) : bool
        {
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)){
                return true;
            } else {
                return false;
            }
        }

        //Проверка передаваемого массива

        /**
         * Проверка, что переданные массивы валидны и содержат только необходимые данные
         * @param array $requiredKeys Массив с ключами, которые должны быть в передаваемом массиве
         * @param array $data Массив, который необходимо проверить на валидность
         * @return bool true, если валиден
         */
        public function checkArrayKeys(array $requiredKeys, array $data) : bool
        {
            if (count(array_intersect_key(array_flip($requiredKeys), $data)) === count($requiredKeys) && count($data) === count($requiredKeys)) {
                return true;
            } else {
                return false;
            }
        }

    }
