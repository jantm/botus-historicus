<?php

$messageParts = [
    'who' => [
        [
            'name' => 'Wincenty Kadłubek',
            'img'  => 'wincenty-kadlubek.jpg',
        ],
        [
            'name' => 'Jan Długosz',
            'img'  => 'jan-dlugosz.jpg',
        ],
        [
            'name' => 'Gall Anonim',
            'img'  => 'gall-anonim.jpg',
        ],
        [
            'name' => 'Norman Davies',
            'img'  => 'norman-davies.jpg',
        ],
        [
            'name' => 'Paweł Jasienica',
            'img'  => 'pawel-jasienica.jpg',
        ],
        [
            'name' => 'Herodot z Halikarnasu',
            'img'  => 'herodot.jpg',
        ],
        [
            'name' => 'Publiusz Korneliusz Tacyt',
            'img'  => 'tacyt.jpg',
        ],
    ],

    'asked' => [
        'zapytany o timestampa z roku',
        'pingnięty w sprawie roku pańskiego',
        'po requeście w kwestii roku',
        'zrobiwszy selecta z tabeli dot. roku pańskiego'
    ],

    'answers' => [
        'z miejsca raczy nas responsem',
        'śle nam łacno taką zwrotkę',
        'prędko bieży z arrayem pełnym ciekawostek',
        'chyżo zwraca infodumpa poniższej zawartości',
    ],

    'attachmentFooter' => [
        'title' => 'Znany historyk radzi:',
        'text'  => [
            'Więcej (i trochę tych samych) informacji znajdziesz w Wikipedii:',
            'Publiusz Korneliusz Tacyt najpewniej użyłby WikiWand (wikiwand.com], żeby poczytać sobie więcej:',
            'Polskie Towarzystwo Historyczne zaleca najpierw robić code review, a dopiero w drugiej kolejności poczytać sobie o ciekawych dziejach:',
        ],
    ],
];

/**
 * Error responses
 */
$errorMessages = [
    'invalidSignature' => 'Obawiam się, że pańska sygnatura slackowa jest inwalidą.',
    'noText'           => 'Historycy piszą historię, ale adres PR-a to już wypadałoby samemu wpisać.',
    'noYear'           => 'Cosik niewyraźnie pisecie, panocku, albo mnie wzrok polecioł, bo jo tu zadnego numeru pijara nie widze.',
    'badRepo'          => 'Niestety, z tej księgi historykom czytać nie dozwolono (czyt. tego repo nie ma na whiteliście prawilności).',
    'noFuture'         => '[[BOT_NAME]] prezentuje dane historyczne i, między nami mówiąc, uważa grzebanie w przyszłości za oszustwo. Od tego są osobne boty-futuryści, ale te zostaną stworzone dopiero w przyszłości.',
    'noPage'           => 'Niestety, ten rok to wciąż niezapisana karta w historii (innymi słowy: nie udało się nic wygrzebać z Wikipedii).',
    'noRepo'           => 'Tylko poprawny adres PR-a z legitymacyjnego repo może dać nam numer roku.',
    'tooManyLinks'     => 'Bez przesady, nie chcemy zanudzać ludzi całym podręcznikiem historii. Jeden link do PR-a, nie więcej. Ostatecznie jeszcze mają zrobić kołdrę wju, a nie tylko czytać o dziejach minionych.',
];

/**
 * Predefined messages for specific dates.
 * Wikipedia is not bothered in those cases, our version of history has higher priority (obviously).
 */
$predefinedDateMessages = [
    1410 => [
        'Jedyne istotne wydarzenia' => 'Tego roku było tylko jedno wydarzenie: GRUNWALD!!! #JagiełłoFTW #JungingenNaKolanach',
    ],
];
