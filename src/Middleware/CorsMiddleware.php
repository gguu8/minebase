<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Mine\Framework\Middleware;

use Hyperf\Context\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $originConfig = config('framework.cors_origin', '*');
        $origin       = 'null';
        if ($originConfig === '*') {
            $origin = '*';
        } else {
            $originReq                                                         = $request->getHeaderLine('Origin');
            in_array($originReq, explode(',', $originConfig), true) && $origin = $originReq;
        }

        $response = Context::get(ResponseInterface::class);
        $response = $response->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader(
                'Access-Control-Allow-Headers',
                'Authorization,Accept,Content-Type,Origin,User-Agent,X-Requested-With,Mines-Corp-Id,Mines-Source-Type'
            )
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');

        Context::set(ResponseInterface::class, $response);

        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            return $response;
        }

        return $handler->handle($request);
    }
}
