<?php declare(strict_types=1);

namespace App\Controller;

use HttpSoft\Response\HtmlResponse;

class SimpleController extends RequestHandler
{
    public function __invoke()
    {
        return new HtmlResponse('About Page');
    }
    
    public function us()
    {
        return new HtmlResponse('About Us');
    }

    public function project()
    {
        return new HtmlResponse('About Project');
    }
}
