<p align="center"> 
 <a href="https://orchid.software/"><img src="./preview.jpg" alt="Preview"></a>
</p>


## Проект

Данный проект представляет собой результат моей профессиональной работы.
Он выполнен в соответствии с передовыми стандартами и использует современные технологии, такие как **PHP версии 8.2** и **Laravel**. Код проекта оформлен в соответствии со стандартом **PSR-12**, и включает настроенный **Code Sniffer** для автоматической проверки соответствия кода этому стандарту. Кроме того, в проекте применяется **PHPStan** версии 8 для статического анализа кода и обеспечения его качества.

## Полученное задание

Написать код на PHP, реализующий REST API, предназначенный для учёта посещений сайта с разбиением по странам.

Сервис должен предоставлять два метода:

- Обновление статистики, принимает один аргумент – код страны (ru, us, it...).
  Предполагаемая нагрузка: 1 000 запросов в секунду.
- Получение собранной статистики по всем странам, возвращает JSON-объект вида:
```json
{ 
  код страны: количество,
  cy: 123,
  us: 456, ... 
}
```
  Предполагаемая нагрузка: 1 000 запросов в секунду.

Хранилище данных: Redis.
Допустимо использование готовых библиотек, фреймворков и т.п..

На оценку влияет готовность к высоким нагрузкам, читаемость кода, обработка ошибок.
Время выполнения: от 2 до 4 часов.

## Установка

```bash
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
php artisak key:generate
```
> В файле .env необходимо установить параметры соединения для Redis

## Оптимизация производительности

Для обеспечения максимальной производительности фреймворка необходимо выполнить:

```bash
php artisan optimize
```

Это позволяет сократить время загрузки страниц и повысить отзывчивость проекта.

## Тесты
```shell
php artisan test
```

## Настройки PHP-FPM

Для наилучшей работы проекта рекомендуется установить оптимальные настройки PHP-FPM, такие как максимальное количество дочерних процессов (pm.max_children), стартовое количество процессов (pm.start_servers), минимальное и максимальное количество свободных процессов (pm.min_spare_servers, pm.max_spare_servers), таймаут ожидания простоя процессов (pm.process_idle_timeout) и максимальное количество запросов на один процесс (pm.max_requests). Эти настройки способствуют стабильной и эффективной работе проекта.

## API Endpoints
**Обновление статистики**
```shell
curl --location --request PATCH 'https://{{host}}/api/countries/ru' \
--header 'Accept: application/json' 
```
**Получение статистики**
```shell
curl --location 'https://{{host}}/api/countries' \
--header 'Accept: application/json'
```
## Хранение
Редис инкриминирует число в int в hash type, его успешное увеличение зависит от архитектуры 
процессора. Если число превышает максимальное, то оно меняться не будет.
Для архитектуры 32 хватит всего на 24 дня при нагрузке 1000 RPC
А для архитектуры 64 на 29247120 лет.
Стоит еще подумать о переполнении.

## Нагрузочное тестирование

Проект успешно прошел нагрузочное тестирование на конфигурации аппаратного обеспечения MacBook Pro с чипом Apple M1 Pro и 16 GB оперативной памяти.
И на mac mini с 3 GHz 6‑ядерным процессором Intel Core i5 и 32 ГБ 2667 MHz DDR4 памяти.

Результаты тестирования подтверждают высокую производительность проекта и его способность эффективно обрабатывать большие нагрузки.

Мне важно уделить внимание производительности и качеству кода в данном проекте, чтобы он мог быть успешно развернут и использован в реальных условиях работы.

Тестирование проводилось с использованием протокола HTTPS, результаты представлены на графике ниже:

### Apache Benchmark на Mac Mini
```text
Benchmarking jobt.test (be patient)
Completed 500 requests
Completed 1000 requests
Completed 1500 requests
Completed 2000 requests
Completed 2500 requests
Completed 3000 requests
Completed 3500 requests
Completed 4000 requests
Completed 4500 requests
Completed 5000 requests
Finished 5000 requests


Server Software:        nginx/1.21.6
Server Hostname:        jobt.test
Server Port:            80

Document Path:          /api/v1/test
Document Length:        14 bytes

Concurrency Level:      50
Time taken for tests:   4.842 seconds
Complete requests:      5000
Failed requests:        0
Total transferred:      1475000 bytes
HTML transferred:       70000 bytes
Requests per second:    1032.65 [#/sec] (mean)
Time per request:       48.419 [ms] (mean)
Time per request:       0.968 [ms] (mean, across all concurrent requests)
Transfer rate:          297.49 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.6      0      38
Processing:     4   48   8.7     46     139
Waiting:        3   48   8.7     46     139
Total:          5   48   8.7     46     139

Percentage of the requests served within a certain time (ms)
  50%     46
  66%     48
  75%     52
  80%     54
  90%     58
  95%     65
  98%     73
  99%     79
 100%    139 (longest request)
```

### Locust на M1
В папке locust можно посмотреть html файлы с тестирования

Тест на запись

<img src="https://github.com/agoalofalife/job-test/blob/main/locust/Screenshot.png">


> Тест можно запустить обычным образом установим `locust`. Далее перейти в папку проекта
> `locust` и выполнить в команду `locust`. Далее веб-интерфейсе задать кол-во пользователей
> шаг с которым они будут добавляться и хост. В текущем тесте было 1000 пользователей с шагом 50 users per seconds.
