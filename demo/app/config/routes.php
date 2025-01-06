<?php

use HttpSoft\Response\HtmlResponse;

return [
    'home'      => ['/', fn() => new HtmlResponse("Hello, world!")],
];
