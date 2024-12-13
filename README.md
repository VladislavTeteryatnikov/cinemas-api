# cinemas-api
___
### Краткое описание проекта
Данный проект учебный. Это API для сети кинотеатров, созданный по архитектуре REST. С помощью данного API можно получить информацию о кинотетрах сети, их залах, сеансах, свободных и занятых местах, а также оформить покупку билета на сеанс. Функционал доступен только авторизованным пользователям.
___

### Стек используемых технологий на проекте
* PHP;
* MySQL
* Git;
___

### Развертывание проекта
1) Клонировать проект c github 
```gitexclude
git clone https://github.com/VladislavTeteryatnikov/cinemas-api.git
```
2) Импортировать базу данных из файла *database/cinema.sql*
3) В файле *configs/constants.php* указать путь API_ROOT.

   Например:
```php
define("API_ROOT", "/test/cinemas-api/");
```
4) В файле *configs/db.php* указать данные для подключения к БД.

   Например:
```php
$db = array(
        'HOST' => 'localhost',
        'USER' => 'root',
        'PASSWORD' => '',
        'DB_NAME' => 'cinema',
        'CHARSET' => 'utf8'
    );
```
___

### ДОКУМЕНТАЦИЯ ПО ИСПОЛЬЗОВАНИЮ
### 1. Авторизация

***GET/auth/?key={...}&application={...}*** - необходимо отправить GET-параметрами key и application, полученные от разработчика для генерации уникального token, который нужно будет указывать при каждом запросе. Для тестирования использовать: key=1234 и application=1.

Пример запроса (http-метод GET):

http://localhost/test/cinemas-api/auth/?key=1234&application=1

### 2. Информация о кинотеатрах сети

***GET/cinemas/{cinemaID}/?token={...}*** - информация о кинотеатрах сети, либо о конкретном кинотеатре (название, адрес).

Пример запроса (http-метод GET):

http://localhost/test/cinemas-api/cinemas/?token=16f486c2dc7facada82d663g8aede399

### 3. Информация о залах кинотеатров

***GET/halls/{hallsID}/?token={...}*** - информация обо всех залах всех кинотеатров сети, либо о конкретном (кинотеатр, название зала и номер зала).

Пример запроса (http-метод GET):

http://localhost/test/cinemas-api/halls/1/?token=16f486c2dc7facada82d663g8aede399

***GET/halls/?token={...}&cinemaID={...}*** - информация о залах конкретного кинотеатра, необходимо передать ID каинотеатра через get-параметр.

Пример запроса (http-метод GET):

http://localhost/test/cinemas-api/halls/?token=16f486c2dc7facada82d663g8aede399&cinemaID=1

### 4. Информация о сеансах

***GET/seances/{seanceID}/?token={...}*** - информация обо всех сеансах, либо о конкретном сеансе (кинотеатр, зал, название фильма, жанр фильма, режиссер, актеры, дата и время сеанса, цена билета, краткое описание фильма и возрастное ограничение).

Пример запроса (http-метод GET):

http://localhost/test/cinemas-api/seances/1/?token=16f486c2dc7facada82d663g8aede399

***GET/seances/?token={...}&date=ГГГГ-ММ-ДД*** - информация обо всех сеансах в указанную дату.

Пример запроса (http-метод GET):

http://localhost/test/cinemas-api/seances/?token=16f486c2dc7facada82d663g8aede399&date=2017-02-02

### 5. Информация о свобоных и занятых местах

***GET/places/?token={...}&seance={seanceID}*** - информация о свободных и занятых местах на сеанс (ряд, место). Занятое место имеет статус "Забронирован" или "Выкуплен", свободное - "Свободно".

Пример запроса (http-метод GET):

http://localhost/test/cinemas-api/places/?token=16f486c2dc7facada82d663g8aede399&seance=1

### 6. Оформление покупки билета

***POST/places/?token={...}*** - покурка билета. В теле запроса в формате json передается информация: ID_seance, ID_status (1 = Забронироан; 2 = Выкуплен), row, number. 

Пример запроса (http-метод POST):

http://localhost/test/cinemas-api/places/?token=16f486c2dc7facada82d663g8aede399

Тело запроса:

{
   "ID_seance":"1",
   "ID_status":"1",
   "row":1,
   "number":"2"
}
