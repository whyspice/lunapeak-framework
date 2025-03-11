![Logo](/public/static/img/logo.png)
# LunaPeak
**LunaPeak** — это легковесный PHP-фреймворк, созданный для быстрой разработки нативных приложений на PHP 8.4. Он сочетает в себе простоту, гибкость и мощь современных инструментов.

---

## Особенности

- **Кастомный роутинг**.
- **Eloquent ORM** для работы с базой данных.
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

**Инструкция в процессе написания и может кардинально отличаться от того, что есть в действительности!**

---

## Установка

1. **Клонируйте репозиторий:**
   ```bash
   git clone https://github.com/spicexgod/LunaPeak.git
   cd LunaPeak

2. **Установите зависимости:**
   ```bash
   composer install

3. **Настройте окружение:**  
Скопируйте .env.example в .env и укажите параметры базы данных
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_DATABASE=lunapeak
   DB_USERNAME=root
   DB_PASSWORD=

4. Создайте структуру базы данных:
   ```bash
   php artisan migrate

---

## Структура проекта
```
/LunaPeak
├── /app
│   ├── /controllers    # Контроллеры
│   ├── /core           # Ядро фреймворка
│   └── /models         # Модели Eloquent
│   └── /middleware
├── /database
│   └── /migrations     # Миграции базы данных
├── /views              # Шаблоны Twig
├── /public             # Публичная директория (точка входа)
├── /vendor             # Зависимости Composer
├── artisan             # CLI
└── .env                # Файл конфигурации
```

---

## Пример использования
