<?php

namespace Tests\Unit;

use App\Jobs\IncrementCountry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use PHPUnit\Framework\TestCase;

class IncrementCountryTest extends TestCase
{

    /**
     * Тестирование базовой функциональности работы Job IncrementCountry.
     * Проверяется увеличение значения счетчика страны 'ru' в Redis дважды
     * @test
     */
    public function expect_increment_counter_up(): void
    {
        Redis::partialMock()->shouldReceive('hIncrBy')->with('countries', 'ru', 1)->times(2);
        Collection::times(2, fn() => (new IncrementCountry('ru'))->handle());
        $this->assertTrue(true);
    }
}
