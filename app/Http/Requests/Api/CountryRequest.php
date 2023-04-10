<?php

namespace App\Http\Requests\Api;

use App\Repositories\CountryRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
{
    /**
     * В Laravel в этом блоке вынесена авторизация запроса, в нормальных проектах я согласен с тем мнением,
     * что это не самое лучшее место для авторизации, так как здесь недостаточно данных для этой операции
     * Чтобы не расписывать подробно, можно ознакомиться вот с этой ссылкой, в конце там абзац который нам нужен,
     * но в целом, чтобы понять контекст надо прочитать несколько глав из этой книги
     * https://github.com/adelf/acwa_book_ru/blob/master/manuscript/4-application-layer.md#%D0%BF%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D1%85-%D0%B7%D0%B0%D0%BF%D1%80%D0%BE%D1%81%D0%B0
     */
    public function authorize(): bool
    {
        // Метод authorize() может быть усложнен для персонализации клиента в запросе,
        // например, увеличение счетчика считается, если клиент еще этого не делал ранее.
        // Для этого можно использовать $request->fingerprint() или другие конкретные параметры клиента,
        // чтобы можно было его персонализировать.
        return true;
    }

    /**
     * Метод prepareForValidation() используется для подготовки данных перед их валидацией.
     * В данном случае мы валидируем данные запроса, добавляя параметр 'country' из URL.
     * Однако, такой подход зависит от соглашений внутри команды и может считаться неоптимальным.
     */
    protected function prepareForValidation()
    {
        $this->merge(['country' => $this->route('country')]);
    }

    /**
     *  Метод rules() возвращает правила валидации для данного запроса.
     *
     * @param CountryRepository $countryRepository
     * @return array
     */
    public function rules(CountryRepository $countryRepository): array
    {
        return [
            'country' => ['required', Rule::in($countryRepository->getCountriesByAlpha2())],
        ];
    }
}
