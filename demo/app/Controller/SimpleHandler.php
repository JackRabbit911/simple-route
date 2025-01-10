<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticlesRepo;
use Az\Route\Route;
use HttpSoft\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;

class SimpleHandler extends RequestHandler
{
    private ArticlesRepo $repo;
    private array $data;

    public function __construct(ArticlesRepo $repo)
    {
        $this->repo = $repo;
        $this->data['menu'] = require '../app/config/menu.php';      
    }

    protected function _before($params)
    {
        $this->data['method'] = $this->request->getMethod();
    }

    public function __invoke()
    {
        $this->data['title'] = 'Homepage';
        $this->data['content'] = '';
        $this->data['pattern'] = '/';
        $this->data['handler'] = __METHOD__;

        return $this->data;
    }

    public function list()
    {
        $list = $this->repo->getList();

        $this->data['title'] = 'Articles list';
        $this->data['content'] = view('list.twig', ['list' => $list], false);
        $this->data['pattern'] = '/articles';
        $this->data['handler'] = __METHOD__;

        return $this->data;
    }

    #[Route(tokens: ['id' => '\d+'])]
    #[Route(filter: __NAMESPACE__ . '\is_set')]
    public function show($id)
    {
        $article = $this->repo->getArticle($id);

        $this->data['title'] = 'Article ' . $id;
        $this->data['content'] = view('article.twig', $article, false);
        $this->data['pattern'] = '/article/{id}/{slug?}';
        $this->data['handler'] = __METHOD__;

        return $this->data;
    }

    #[Route(methods: 'post')]
    public function save()
    {
        return new HtmlResponse('Saved!');
    }

    protected function _after($data)
    {
        return ($data instanceof ResponseInterface) ? $data : view('home.twig', $data);
    }
}

function is_set($route)
{
    $id = $route->getParameters()['id'];
    $article = (new ArticlesRepo())->getArticle($id);

    return $article ? true : false;
}
