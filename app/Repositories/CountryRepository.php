<?php

declare(strict_types=1);

namespace App\Repositories;

use Monarobase\CountryList\CountryListFacade;

/**
 * Класс CountryRepository представляет собой слой абстракции для доступа к данным о странах.
 * В текущей задаче данный слой может показаться излишним, так как задача проста, и данные могут быть получены
 * напрямую в контроллере. Однако, использование репозитория позволяет абстрагироваться от конкретного способа
 * хранения данных, таким образом, данные могут быть помещены в базу данных, Redis, файлы или другие источники,
 * и реализация репозитория может быть легко подменена при необходимости.
 */
class CountryRepository
{
    /**
     * Метод getCountriesByAlpha2() возвращает список стран, отсортированных по алфавитному коду ISO 3166-1 alpha-2.
     * Для упрощения примера, данные получаются из внешней библиотеки Monarobase\CountryList,
     * которая возвращает список стран в формате PHP массива.
     * Размер этого массива составляет 6.5 килобайт.
     *
     * @return array<int, string>
     */
    public function getCountriesByAlpha2(): array
    {
        return collect(CountryListFacade::getList(
            locale: 'en', // Можно не указывать эти параметры, но вопрос, что лучше в клиентском коде читать
            format: 'php' // Явное указание или скрытое по-умолчанию
        ))
            ->keys()
            ->sortKeys()
            ->map(fn ($countryCode) => strtolower($countryCode))
            ->toArray();
    }
}
