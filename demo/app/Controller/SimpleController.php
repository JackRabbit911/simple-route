<?php declare(strict_types=1);

namespace App\Controller;

use Az\Route\Route;
use Psr\Http\Message\ServerRequestInterface;

class SimpleController
{
    private array $data;

    public function __construct(ServerRequestInterface $request)
    {
        $this->data['menu'] = require '../app/config/menu.php';
        $this->data['method'] = $request->getMethod();
    }

    public function __invoke()
    {
        $this->data['title'] = 'About page';
        $this->data['pattern'] = '/about/{action?}';
        $this->data['handler'] = __METHOD__;
        return view('home.twig', $this->data);
    }

    public function us()
    {
        $this->data['title'] = 'About Us';
        $this->data['pattern'] = '/about/{action?}';
        $this->data['handler'] = __METHOD__;
        return view('home.twig', $this->data);
    }

    public function project()
    {
        $this->data['title'] = 'About Project';
        $this->data['pattern'] = '/about/{action?}';
        $this->data['handler'] = __METHOD__;
        return view('home.twig', $this->data);
    }

    #[Route(methods: 'post')]
    public function save()
    {
        $this->data['title'] = 'Saved!';
        $this->data['pattern'] = '/about/project';
        $this->data['handler'] = __METHOD__;
        return view('home.twig', $this->data);
    }
}
