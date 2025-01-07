<?php declare(strict_types=1);

namespace App\Controller;

use Az\Route\Route;
use HttpSoft\Response\HtmlResponse;

class SimpleHandler
{
    public function __invoke()
    {
        return new HtmlResponse('Articles list');
    }

    #[Route(tokens: ['id' => '\d+'])]
    public function show($id)
    {
        return new HtmlResponse('Article ' . $id);
    }
}
