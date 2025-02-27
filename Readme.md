![Logo](/public/static/img/logo.png)
# LunaPeak
**LunaPeak** — это легковесный PHP-фреймворк, созданный для быстрой разработки нативных приложений на PHP 8.4. Он сочетает в себе простоту, гибкость и мощь современных инструментов.

---

## Особенности

- **Кастомный роутинг**.
- **ORM RedBeanPHP** для простой работы с базой данных.
- **Шаблонизатор Twig** для рендеринга представлений.
- **Отладка с Tracy** для удобного анализа ошибок.
- **Система очередей** на основе Redis.
- **PSR-4 автозагрузка** для классов приложения.

---

## Требования

- PHP 8.4
- MySQL
- Redis
- Composer

---

## Установка

1. **Клонируйте репозиторий:**
   ```bash
   git clone https://github.com/spicexgod/LunaPeak.git
   cd LunaPeak
   
2. **Настройка:**
- Отредактируйте файлы config/app.php, config/database.php и config/queue.php, указав свои параметры (URL приложения, данные для подключения к базе данных и Redis).

---

## Структура проекта
```
/LunaPeak
├── /app
│   ├── /controllers    # Контроллеры
│   ├── /core           # Ядро фреймворка
│   ├── /models         # Модели для работы с данными
│   └── /views          # Шаблоны Twig
├── /config             # Файлы конфигурации
├── /public             # Публичная директория (точка входа)
├── /vendor             # Зависимости Composer
└── worker.php          # Скрипт для обработки очередей
```

---

## Пример использования

1. **Создание маршрута и контроллера:**  
    Добавьте маршрут в public/index.php:
    ```
    $router->get('/hello', 'HelloController@index');
    ```
    
    Создайте контроллер app/controllers/HelloController.php:
    ```
    <?php
    namespace App\Controllers;
    
    class HelloController {
        public function index() {
            return 'hello.twig';
        }
    }
    ```
    
    Создайте шаблон app/views/hello.twig:
    ```
    <h1>Hello, LunaPeak!</h1>
    ```

2. **Работа с API:**  
   Маршрут для API:
    ```
    $router->get('/api/user', 'ApiController@getUser');
    ```
   Контроллер app/controllers/ApiController.php:
    ```
    <?php
    namespace App\Controllers;
    
    class ApiController {
       public function getUser() {
          return ['id' => 1, 'name' => 'Luna'];
       }
    }
    ```
   Запрос GET /api/user вернет JSON:
    ```
    {"id": 1, "name": "Luna"}
    ```

3. **Работа с очередью:**  
    ...