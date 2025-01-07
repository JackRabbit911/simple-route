<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\AboutRepo;
use HttpSoft\Response\HtmlResponse;

class SimpleController extends RequestHandler
{
    public function __invoke()
    {
        return new HtmlResponse('About Page');
    }

    public function us(AboutRepo $repo)
    {
        $str = $repo->getAboutUs();
        return new HtmlResponse($str);
    }

    public function project()
    {
        return new HtmlResponse('About Project');
    }
}
