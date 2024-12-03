<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Полето :attribute трябва да бъде прието.',
    'accepted_if' => 'Полето :attribute трябва да бъде прието, когато :other е :value.',
    'active_url' => 'Полето :attribute трябва да е валиден URL адрес.',
    'after' => 'Полето :attribute трябва да бъде дата след :date.',
    'after_or_equal' => 'Полето :attribute трябва да бъде дата след или равна на :date.',
    'alpha' => 'Полето :attribute трябва да съдържа само букви.',
    'alpha_dash' => 'Полето :attribute трябва да съдържа само букви, цифри, тирета и долни черти.',
    'alpha_num' => 'Полето :attribute трябва да съдържа само букви и цифри.',
    'array' => 'Полето :attribute трябва да е масив.',
    'ascii' => 'Полето :attribute трябва да съдържа само еднобайтови буквено-цифрови знаци и символи.',
    'before' => 'Полето :attribute трябва да бъде дата преди :date.',
    'before_or_equal' => 'Полето :attribute трябва да е дата преди или равна на :date.',
    'between' => [
        'array' => 'Полето :attribute трябва да има между :min и :max елементи.',
        'file' => 'Полето :attribute трябва да е между :min и :max килобайта.',
        'numeric' => 'Полето :attribute трябва да е между :min и :max.',
        'string' => 'Полето :attribute трябва да е между знаци :min и :max.',
    ],
    'boolean' => 'Полето :attribute трябва да е true или false.',
    'can' => 'Полето :attribute съдържа неразрешена стойност.',
    'confirmed' => 'Потвърждението на полето :attribute не съвпада.',
    'contains' => 'В полето :attribute липсва задължителна стойност.',
    'current_password' => 'Паролата е неправилна.',
    'date' => 'Полето :attribute трябва да е валидна дата.',
    'date_equals' => 'Полето :attribute трябва да бъде дата, равна на :date.',
    'date_format' => 'Полето :attribute трябва да съответства на формата :format.',
    'decimal' => 'Полето :attribute трябва да има :decimal десетични знаци.',
    'declined' => 'Полето :attribute трябва да бъде отхвърлено.',
    'declined_if' => 'Полето :attribute трябва да бъде отхвърлено, когато :other е :value.',
    'different' => 'Полето :attribute и :other трябва да са различни.',
    'digits' => 'Полето :attribute трябва да бъде :digits цифри.',
    'digits_between' => 'Полето :attribute трябва да е между :min и :max цифри.',
    'dimensions' => 'Полето :attribute има невалидни размери на изображението.',
    'distinct' => 'Полето :attribute има дублирана стойност.',
    'doesnt_end_with' => 'Полето :attribute не трябва да завършва с едно от следните: :values.',
    'doesnt_start_with' => 'Полето :attribute не трябва да започва с едно от следните: :values.',
    'email' => 'Полето :attribute трябва да е валиден имейл адрес.',
    'ends_with' => 'Полето :attribute трябва да завършва с едно от следните: :values.',
    'enum' => 'Избраният :attribute е невалиден.',
    'exists' => 'Избраният :attribute е невалиден.',
    'extensions' => 'Полето :attribute трябва да има едно от следните разширения: :values.',
    'file' => 'Полето :attribute трябва да е файл.',
    'filled' => 'Полето :attribute трябва да има стойност.',
    'gt' => [
        'array' => 'Полето :attribute трябва да има повече от :value елементи.',
        'file' => 'Полето :attribute трябва да е по-голямо от :value килобайта.',
        'numeric' => 'Полето :attribute трябва да е по-голямо от :value.',
        'string' => 'Полето :attribute трябва да е по-голямо от знаците :value.',
    ],
    'gte' => [
        'array' => 'Полето :attribute трябва да има елементи :value или повече.',
        'file' => 'Полето :attribute трябва да е по-голямо или равно на :value килобайта.',
        'numeric' => 'Полето :attribute трябва да е по-голямо или равно на :value.',
        'string' => 'Полето :attribute трябва да е по-голямо или равно на знаците :value.',
    ],
    'hex_color' => 'Полето :attribute трябва да е с валиден шестнадесетичен цвят.',
    'image' => 'Полето :attribute трябва да е изображение.',
    'in' => 'Избраният :attribute е невалиден.',
    'in_array' => 'Полето :attribute трябва да съществува в :other.',
    'integer' => 'Полето :attribute трябва да е цяло число.',
    'ip' => 'Полето :attribute трябва да е валиден IP адрес.',
    'ipv4' => 'Полето :attribute трябва да е валиден IPv4 адрес.',
    'ipv6' => 'Полето :attribute трябва да е валиден IPv6 адрес.',
    'json' => 'Полето :attribute трябва да е валиден JSON низ.',
    'list' => 'Полето :attribute трябва да бъде списък.',
    'lowercase' => 'Полето :attribute трябва да е с малки букви.',
    'lt' => [
        'array' => 'Полето :attribute трябва да има по-малко от :value елементи.',
        'file' => 'Полето :attribute трябва да е по-малко от :value килобайта.',
        'numeric' => 'Полето :attribute трябва да е по-малко от :value.',
        'string' => 'Полето :attribute трябва да съдържа по-малко от :value знаци.',
    ],
    'lte' => [
        'array' => 'Полето :attribute не трябва да има повече от :value елементи.',
        'file' => 'Полето :attribute трябва да е по-малко или равно на :value килобайта.',
        'numeric' => 'Полето :attribute трябва да е по-малко или равно на :value.',
        'string' => 'Полето :attribute трябва да е по-малко или равно на знаци :value.',
    ],
    'mac_address' => 'Полето :attribute трябва да е валиден MAC адрес.',
    'max' => [
        'array' => 'Полето :attribute не трябва да има повече от :max елементи.',
        'file' => 'Полето :attribute не трябва да е по-голямо от :max килобайта.',
        'numeric' => 'Полето :attribute не трябва да е по-голямо от :max.',
        'string' => 'Полето :attribute не трябва да е по-голямо от :max знаци.',
    ],
    'max_digits' => 'Полето :attribute не трябва да има повече от :max цифри.',
    'mimes' => 'Полето :attribute трябва да е файл от тип: :values.',
    'mimetypes' => 'Полето :attribute трябва да е файл от тип: :values.',
    'min' => [
        'array' => 'Полето :attribute трябва да има поне :min елементи.',
        'file' => 'Полето :attribute трябва да е поне :min килобайта.',
        'numeric' => 'Полето :attribute трябва да е поне :min.',
        'string' => 'Полето :attribute трябва да съдържа поне :min знака.',
    ],
    'min_digits' => 'Полето :attribute трябва да има поне :min цифри.',
    'missing' => 'Полето :attribute трябва да липсва.',
    'missing_if' => 'Полето :attribute трябва да липсва, когато :other е :value.',
    'missing_unless' => 'Полето :attribute трябва да липсва, освен ако :other е :value.',
    'missing_with' => 'Полето :attribute трябва да липсва, когато присъства :values.',
    'missing_with_all' => 'Полето :attribute трябва да липсва, когато присъстват :values.',
    'multiple_of' => 'Полето :attribute трябва да е кратно на :value.',
    'not_in' => 'Избраният :attribute е невалиден.',
    'not_regex' => 'Форматът на полето :attribute е невалиден.',
    'numeric' => 'Полето :attribute трябва да е число.',
    'password' => [
        'letters' => 'Полето :attribute трябва да съдържа поне една буква.',
        'mixed' => 'Полето :attribute трябва да съдържа поне една главна и една малка буква.',
        'numbers' => 'Полето :attribute трябва да съдържа поне едно число.',
        'symbols' => 'Полето :attribute трябва да съдържа поне един символ.',
        'uncompromised' => 'Даденият :attribute се появи при изтичане на данни. Моля, изберете различен :attribute.',
    ],
    'present' => 'Полето :attribute трябва да присъства.',
    'present_if' => 'Полето :attribute трябва да присъства, когато :other е :value.',
    'present_unless' => 'Полето :attribute трябва да присъства, освен ако :other е :value.',
    'present_with' => 'Полето :attribute трябва да присъства, когато присъства :values.',
    'present_with_all' => 'Полето :attribute трябва да присъства, когато има :values.',
    'prohibited' => 'Полето :attribute е забранено.',
    'prohibited_if' => 'Полето :attribute е забранено, когато :other е :value.',
    'prohibited_unless' => 'Полето :attribute е забранено, освен ако :other е в :values.',
    'prohibits' => 'Полето :attribute забранява присъствието на :other.',
    'regex' => 'Форматът на полето :attribute е невалиден.',
    'required' => 'Полето :attribute е задължително.',
    'required_array_keys' => 'Полето :attribute трябва да съдържа записи за: :values.',
    'required_if' => 'Полето :attribute е задължително, когато :other е :value.',
    'required_if_accepted' => 'Полето :attribute е задължително, когато се приема :other.',
    'required_if_declined' => 'Полето :attribute е задължително, когато :other е отхвърлено.',
    'required_unless' => 'Полето :attribute е задължително, освен ако :other е в :values.',
    'required_with' => 'Полето :attribute е задължително, когато присъства :values.',
    'required_with_all' => 'Полето :attribute е задължително, когато присъстват :values.',
    'required_without' => 'Полето :attribute е задължително, когато :values ​​не присъства.',
    'required_without_all' => 'Полето :attribute е задължително, когато не присъства нито една от :values.',
    'same' => 'Полето :attribute трябва да съответства на :other.',
    'size' => [
        'array' => 'Полето :attribute трябва да съдържа елементи :size.',
        'file' => 'Полето :attribute трябва да бъде :size килобайта.',
        'numeric' => 'Полето :attribute трябва да бъде :size.',
        'string' => 'Полето :attribute трябва да съдържа знаци :size.',
    ],
    'starts_with' => 'Полето :attribute трябва да започва с едно от следните: :values.',
    'string' => 'Полето :attribute трябва да е низ.',
    'timezone' => 'Полето :attribute трябва да е валидна часова зона.',
    'unique' => 'Атрибутът : вече е зает.',
    'uploaded' => 'Качването на :attribute не бе успешно.',
    'uppercase' => 'Полето :attribute трябва да е с главни букви.',
    'url' => 'Полето :attribute трябва да е валиден URL адрес.',
    'ulid' => 'Полето :attribute трябва да е валиден ULID.',
    'uuid' => 'Полето :attribute трябва да е валиден UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
