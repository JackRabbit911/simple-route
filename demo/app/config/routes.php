<?php

use HttpSoft\Response\HtmlResponse;

return [
    'home'      => ['/', fn() => new HtmlResponse("Hello, world!")],
    'articles'  => ['/articles', fn() => new HtmlResponse('Articles page'), 'post'],
];
