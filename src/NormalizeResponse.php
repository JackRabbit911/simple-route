<?php

namespace Az\Route;

use HttpSoft\Response\HtmlResponse;
use HttpSoft\Response\JsonResponse;
use HttpSoft\Response\TextResponse;
use HttpSoft\Response\XmlResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

trait NormalizeResponse
{
    private function normalizeResponse(ServerRequestInterface $request, mixed $response): ResponseInterface
    {
        if ($response instanceof ResponseInterface) {
            return $response;
        }
        
        $accept_header = $request->getHeaderLine('Accept');
        $mimeTypes = $this->getSortedMimeTypesByHeader($accept_header);

        foreach ($mimeTypes as $mimeType) {
            return match ($mimeType) {
                'text/html', '*/*' => new HtmlResponse($response),
                'text/plain' => new TextResponse($response),
                'application/json' => new JsonResponse($response),
                'application/xml', 'text/xml' => new XmlResponse($response),
            };
        }

        return new HtmlResponse($response);
    }

    private function getSortedMimeTypesByHeader($accept_header): array
    {
        if (!$accept_header) {
            return [];
        }

        $mimeTypes = [];

        foreach (explode(',', $accept_header) as $acceptParameter) {
            $parts = explode(';', $acceptParameter);

            if (!isset($parts[0]) || isset($mimeTypes[$parts[0]]) || !($mimeType = strtolower(trim($parts[0])))) {
                continue;
            }

            if (!isset($parts[1])) {
                $mimeTypes[$mimeType] = 1.0;
                continue;
            }

            if (preg_match('/^\s*q=\s*(0(?:\.\d{1,3})?|1(?:\.0{1,3})?)\s*$/i', $parts[1], $matches)) {
                $mimeTypes[$mimeType] = (float) ($matches[1] ?? 1.0);
            }
        }

        uasort($mimeTypes, static fn(float $a, float $b) => ($a === $b) ? 0 : ($a > $b ? -1 : 1));
        return array_keys($mimeTypes);
    }
}
