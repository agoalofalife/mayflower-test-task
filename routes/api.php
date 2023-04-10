<?php

use App\Http\Controllers\Api\CountryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

// Группировка маршрутов с префиксом '/v1'
// можно ограничивать кол-во запросов, в ответе будет Too Many Requests
Route::middleware('throttle:1000')->group(['prefix' => 'v1'], function (Router $router) {
    // Используется ресурсный контроллер 'CountryController' из Laravel, однако некоторым разработчикам может показаться, что
    // стандартные методы ресурсного контроллера недостаточно наглядны, и требуется запомнить соответствия между методами HTTP-запросов
    // и методами контроллера. Для более явного указания доступных методов может быть полезно ограничить ресурс только определенными
    // методами, например, только 'index' и 'update' методами.
    $router->resource('countries', CountryController::class)->only('index', 'update');

    // Возникает вопрос о выборе между методами HTTP-запросов 'PATCH' и 'POST'. Согласно RFC 5789, 'PATCH' считается более предпочтительным
    // для неидемпотентных запросов, таких как обновление счетчика. Однако, в Laravel для реализации такого функционала проще использовать 'POST',
    // так как позволяет избежать сложностей с валидацией и написанием тестов. Некоторые сервисы, такие как Stripe, также используют 'POST' для
    // обновления ресурсов, несмотря на рекомендации RFC 5789.

    // Пример альтернативного варианта маршрутов:
    // Route::patch('countries', [CountryController::class, 'incrementByCountry'])->name('country.increment');
    // Route::get('countries', [CountryController::class, 'getStats'])->name('countries.get-stats');
});
