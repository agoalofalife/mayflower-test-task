<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CountryRequest;
use App\Jobs\IncrementCountry;
use App\Repositories\CountryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
use RedisException;

class CountryController extends Controller
{
    /**
     * @return JsonResponse
     * @throws RedisException
     */
    public function index(): JsonResponse
    {
        $stats = Redis::hGetAll('countries');

        return response()->json($stats);
    }

    /**
     * Метод обновления данных о стране.
     *
     * @param CountryRequest $request
     * @return JsonResponse
     * @throws RedisException
     */
    public function update(CountryRequest $request): JsonResponse
    {
        // Как и полагается мы можем усложнить персонализацию клиента в запросе
        // например увеличение счетчика считается, в случае если клиент еще этого не делал ранее
        // Для примера использовать $request->fingerprint() или какие нибудь конкретные параметры клиента
        // чтобы можно было его персонализировать

        // Так же я не стал тут усложнять абстракциями в виде способа хранения это счетчика.
        // Нам бы это хорошо пригодилось в тестировании, чтобы например использовать оперативную память
        // для более быстрого тестирования

        // Используем метод afterResponse() для выполнения операции после отправки HTTP-ответа,
        // чтобы избежать задержки загрузки пользователя
        IncrementCountry::dispatchAfterResponse($request->validated('country'));


        // Обработка ошибок, предполагает что будет выброшено исключение, которое будет передано в глобальный перехватчик
        // и ошибки должны быть отправлены в прометей/sentry или другой инструмент для дальнейшей обработки

        // Некоторые разработчики предпочитают использовать Response::HTTP_OK вместо текстовых значений,
        // чтобы избежать магических чисел и обеспечить более читаемый код
        return response()->json(['status' => 'success']);
    }


    /**
     * Из предложенного задания не ясно, необходимость отдачи всех стран или только тех, что хранятся в Redis
     * По этому написал и менее предпочтительный вариант который делает запросы для всех стран и пустые заполняет нулевым значением.
     *
     * @param CountryRepository $countryRepository
     *
     * @return JsonResponse
     */
    public function indexAlternative(CountryRepository $countryRepository): JsonResponse
    {
        $statsByCountries = Redis::hGetAll('countries');

        $countriesByAlpha2 = $countryRepository->getCountriesByAlpha2();
        // 1 вариант попроще с коллекцией
        // collect($countriesByAlpha2)->mapWithkeys(fn($key) => [$key => 0])->merge($statsByCountries);

        // 2 вариант по-сложнее на массивах
        // все это нужно, чтобы показать список стран и их кол-во, там где нет данных
        // по-умолчанию ставится 0
        // все это делается таким образом - чтобы оставить сортировку
        // так же можно задать все значением 0 в самом redis и делать просто Facades\Redis::hGetAll('countries');
        foreach ($countriesByAlpha2 as $index => $countryByAlpha2) {
            if (array_key_exists($countryByAlpha2, $statsByCountries)) {
                // ставим значение из redis
                $countriesByAlpha2[$countryByAlpha2] = $statsByCountries[$countryByAlpha2];
            } else {
                // ставим 0 там где нет данных по стране
                $countriesByAlpha2[$countryByAlpha2] = 0;
            }
            // удаляем старое значение из общего списка стран
            unset($countriesByAlpha2[$index]);
        }
        return response()->json($countriesByAlpha2);
    }
}
