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
        'title' => 'Article 2 with slug',
        'href'  => path('article', ['id' => 2, 'slug' => 'article-foo-bar']),
        'route' => 'article',
    ],
    [
        'title' => 'Article 3 (Page not found)',
        'href'  => path('article', ['id' => 3]),
        'route' => 'article',
    ],
    [
        'title' => 'Article (Page not found)',
        'href'  => path('article', ['id' => 'foo']),
        'route' => 'article',
    ],
    [
        'title' => 'Save article (Method not allowed)',
        'href'  => path('save'),
    ],
    [
        'title' => 'File JPEG',
        'href'  => path('file', ['file' => 'uploads/zay.jpg']),
    ],
    [
        'title' => 'File PDF',
        'href'  => path('file', ['file' => 'uploads/git.pdf']),
    ],
    [
        'title' => 'Missing file',
        'href'  => path('file', ['file' => 'uploads/no_file.jpg']),
    ],
];
