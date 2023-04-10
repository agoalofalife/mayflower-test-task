<?php

namespace Tests\Feature\Api;

use App\Jobs\IncrementCountry;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;

class CountryTest extends TestCase
{
    /**
     * Вопрос underscore or camelCase
     * Есть интересная статься на эту тему https://stitcher.io/blog/have-you-thought-about-casing
     * @test
     */
    public function expect_422_because_countries_do_not_exist(): void
    {
        // Вопрос о том, указывать ли route('country.store') или /api/v1/countries, является спорным
        // В идеале, для настоящего функционального тестирования рекомендуется указывать именно URL,
        // однако мы не хотим менять его каждый раз в тестах
        // Я руководствовался хорошей книгой по юнит-тестированию и частично функциональному
        // тестированию: https://www.labirint.ru/books/777259/
         $this->patch(route('countries.update', ['country' => 'blablabla']), [], [
             'Accept' => 'application/json',
         ])->assertUnprocessable()
           ->assertJsonStructure([
               'message',
               'errors' => [
                   'country'
               ]
           ]);
    }

    /**
     * @test
     */
    public function expect_201_success_update_counter(): void
    {
        Bus::fake();

        $this->patch(route('countries.update', ['country' => 'ru']))->assertOk();
        Bus::assertDispatchedAfterResponseTimes(IncrementCountry::class, 1);
    }


    /**
     * @test
     */
    public function expect_200_success_index_countries(): void
    {
        // в идеальном мире мы должны или тестировать в другом хранилище и подменять реализацию
        // или тестировать как в production(redis) что является более настоящим с точки зрения тестирования
        Redis::hSet('countries', 'ru', 122);

        $this->get(route('countries.index'))->assertJson([
            'ru' => 122,
        ]);
    }

//    /**
//     * @test
//     * Это был бы идеальный тест, с точки зрения функционального теста
//     *
//     */
//    public function expect_success_index_countries(): void
//    {
//        Redis::hSet('countries', 'ru', 0);
//        Collection::times(100, fn () => $this->patch(route('countries.update', ['country' => 'ru']))->assertOk());
//        $this->get(route('countries.index'))->assertJson([
//            'ru' => 199,
//        ]);
//    }
}
