<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Mine\Framework\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{

     #[Inject]
    protected ContainerInterface $container;

     #[Inject]
    protected RequestInterface $request;

     #[Inject]
    protected ResponseInterface $response;
    
    protected function success($data = [],$message="success",$code=200)
    {
        return $this->response->json([
            'code' => $code,
            'msg'  => $message,
            'data' => $data,
        ]);
    }

    protected function fail($code,$message = 'fail')
    {
        return $this->response->json([
            'code' => $code,
            'message' => $message,
        ]);
    }

     protected function redirect($url, $status = 302)
     {
         return $this->response()
             ->withAddedHeader('Location', (string) $url)
             ->withStatus($status);
     }

     protected function cookie(Cookie $cookie)
     {
         $response = $this->response()->withCookie($cookie);
         Context::set(PsrResponseInterface::class, $response);
         return $this;
     }

    
}
