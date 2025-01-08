<?php declare(strict_types=1);

namespace App\Controller;

use Az\Route\Route;
use HttpSoft\Response\HtmlResponse;

#[Route(methods: 'any')]
class SimpleHandler
{
    public function __invoke()
    {
        return new HtmlResponse('Articles list');
    }

    // #[Route(methods: ['post', 'put'])]
    public function show($id)
    {
        return new HtmlResponse('Article ' . $id);
    }
}
