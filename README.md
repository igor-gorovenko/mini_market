# Mini market

Это площадка, которая позволяет выставлять товары для пользователей, с возможностью выставить минимальную цену.

## Функции

- Можно выставить товар, на продажу
- Выставить минимальную стоимость товара
- Пользователь может заплатить столько, сколько хочет

## Getting Started

#### 1. Clone this repository:

Замените username на свой

```
git clone https://github.com/username/mini_market.git
```

Переходим в скопированный проект, в корневую папку

```
cd mini_market
```

#### 2. Install dependencies:

Устанавливаем composer

```
composer install
```

Копируем .env.example и меняем имя: .env

Генерируем APP_KEY в .env файле

```
php artisan key:generate
```

Устанавливаем npm и делаем сборку проекта
```
npm install
npm run build
```

Выполняем миграции

```
php artisan migrate
```

#### 3. Generate data

Генерируем данные
```
php artisan db:seed
```

Создаем символическую ссылку
```
php artisan storage:link
```


#### 4. Stripe

Устанавливаем Stripe

```
composer require stripe/stripe-php
```

Добавить ключи

Добавьте ключи из админки stripe, на страницу сервиса Settings.

Ключи можно найти в админке Stripe -> Developer -> API Keys
Publishable key и Secret key

#### 5. Запускаем проект

Запустить сервер с помощью artisan или своим способом
```
php artisan serve
```

Пользователи для входа
```
// Admin

admin@example.com
test1234

// User

user@example.com
test1234
```