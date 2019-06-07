<?php

$messageParts = [
    'who' => [
        [
            'name' => 'Norman Davies',
            'img'  => 'norman-davies.jpg',
        ],
        [
            'name' => 'Herodotus',
            'img'  => 'herodot.jpg',
        ],
        [
            'name' => 'Publius Cornelius Tacitus',
            'img'  => 'tacyt.jpg',
        ],
    ],

    'asked' => [
        'when asked about the timestamp',
        'questioned on the year of our Lord',
        'on hearing request regarding anno domini',
        'after running a query regarding the year',
    ],

    'answers' => [
        'immediately sends an array full of curiosities',
        'presents us with the following response',
        'dumps this information on our laps',
        'gives us quickly an interesting piece of information',
    ],

    'attachmentFooter' => [
        'title' => 'Famous historian says:',
        'text'  => [
            'One can find more information in Wikipedia, if one wishes to learn about the acts of our ancestors:',
            'Publius Cornelius Tacitus would most likely use WikiWand (wikiwand.com) to read more:',
            'The World History Association recommends to finish the code review before reading more about interesting times:',
        ],
    ],
];

/**
 * Error responses
 */
$errorMessages = [
    'invalidSignature' => 'I\'m afraid that your Slack signature is invalid.',
    'noText'           => 'Historians write history, but it\'s up to you to provide a PR link.',
    'noYear'           => 'Either your handwriting is a tad lopsided or my eyes are not as they used to be, but I cannot see a PR number anywhere in that message.',
    'badRepo'          => 'Unfortunately, that\'s one of the books our historians are forbidden to read (meaning the provided repository is not whitelisted in the config).',
    'noFuture'         => '[[BOT_NAME]] presents historical data and frankly, it considers looking into the future cheating. There are specialized bots-futurists for such tasks, but they\'re to be created (obviously).',
    'noPage'           => 'Unfotunately, this year is still to come (in other words, we found nothing in Wikipedia).',
    'noRepo'           => 'Only a valid PR link refering to a whitelisted repository can give us a proper year.',
    'tooManyLinks'     => 'Puh-lease, you don\'t want to bore everyone with the whole history handbook. One PR link per message will suffice. After all, people are meant to do a code review here.',
];

/**
 * Predefined messages for specific dates.
 * Wikipedia is not bothered in those cases, our version of history has higher priority (obviously).
 */
$predefinedDateMessages = [
    1605 => [
        'Guy Fawkes attempted to blow up the House of Lords.',
    ],
];
