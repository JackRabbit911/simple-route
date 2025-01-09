<?php

return [
    [
        'title' => 'Homepage',
        'href'  => path('home'),
    ],
    [
        'title' => 'Articles',
        'href'  => path('articles'),
    ],
    [
        'title' => 'Article 1',
        'href'  => path('article', ['id' => 1]),
        'route' => 'article',
    ],
    [
        'title' => 'Article 2',
        'href'  => path('article', ['id' => 2]),
        'route' => 'article',
    ],
    [
        'title' => 'Article 3 (not found)',
        'href'  => path('article', ['id' => 3]),
        'route' => 'article',
    ],
    [
        'title' => 'Article (not found)',
        'href'  => path('article', ['id' => 'foo']),
        'route' => 'article',
    ],
    [
        'title' => 'Save article 3',
        'href'  => path('article'),
    ],
];
