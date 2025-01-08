<?php declare(strict_types=1);

namespace App;

use HttpSoft\Emitter\EmitterInterface;
use HttpSoft\Response\HtmlResponse;
use HttpSoft\Runner\MiddlewarePipelineInterface;
use HttpSoft\Runner\MiddlewareResolverInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class App
{
    private ServerRequestInterface $request;
    private MiddlewarePipelineInterface $pipeline;
    private MiddlewareResolverInterface $resolver;
    private EmitterInterface $emitter;

    public function __construct(
        ServerRequestInterface $request,
        MiddlewarePipelineInterface $pipeline,
        MiddlewareResolverInterface $resolver,
        EmitterInterface $emitter
    )
    {
        $this->request = $request;
        $this->pipeline = $pipeline;
        $this->resolver = $resolver;
        $this->emitter = $emitter;

        require '../app/library.php';
    }

    public function run(): void
    {
        require '../app/config/pipeline.php';
        $response = $this->pipeline->process($this->request, self::defaultHandler());
        $this->emitter->emit($response);
    }

    public function pipe($middleware, string $path = null): void
    {
        $this->pipeline->pipe($this->resolver->resolve($middleware), $path);
    }

    public static function defaultHandler(): RequestHandlerInterface
    {
        return new class() implements RequestHandlerInterface
        {
            private array $reasonPhrase = [
                404 => 'Page not found',
                405 => 'Method not allowed',
            ];

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $code = $request->getAttribute('status_code') ?? 404;
                $headers = $request->getAttribute('headers');

                $data = [
                    'code' => $code,
                    'msg' => $this->reasonPhrase[$code],
                ];

                $str = render('../app/views/error.php', $data);
                $response = new HtmlResponse($str);

                if ($headers) {
                    foreach ($headers as $name => $value) {
                        $response = $response->withAddedHeader($name, $value);
                    }
                }

                return $response;
            }
        };
    }
}
