<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticlesRepo;
use Az\Route\Route;
use HttpSoft\Response\HtmlResponse;

class SimpleHandler
{
    private ArticlesRepo $repo;
    private array $menu;

    public function __construct(ArticlesRepo $repo)
    {
        $this->repo = $repo;
        $this->menu = require '../app/config/menu.php';
    }

    public function __invoke()
    {
        $data = [
            'title' => 'Homepage',
            'menu'  => $this->menu,
            'cont'  => '',
        ];

        return view('home.twig', $data);
    }

    public function list()
    {
        $list = $this->repo->getList();

        $data = [
            'title' => 'Articles list',
            'menu'  => $this->menu,
            'cont'  => view('list.twig', ['list' => $list], false),
        ];

        return view('home.twig', $data);
    }

    #[Route(tokens: ['id' => '\d+'])]
    #[Route(filter: __NAMESPACE__ . '\is_set')]
    public function show($id)
    {
        $article = $this->repo->getArticle($id);

        $data = [
            'title' => 'Article ' . $id,
            'menu'  => $this->menu,
            'cont'  => view('article.twig', $article, false),
        ];
        
        return view('home.twig', $data);
    }

    #[Route(methods: 'post')]
    public function save()
    {
        return new HtmlResponse('Saved!');
    }
}

function is_set($route)
{
    $id = $route->getParameters()['id'];
    $article = (new ArticlesRepo())->getArticle($id);

    return $article ? true : false;
}
