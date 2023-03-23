<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Mine\Framework;

use Mine\Framework\Exception\Handler\AuthExceptionHandler;
use Mine\Framework\Exception\Handler\CommonExceptionHandler;
use Mine\Framework\Exception\Handler\GuzzleRequestExceptionHandler;
use Mine\Framework\Exception\Handler\ValidationExceptionHandler;
use Mine\Framework\Exception\Handler\ErrorExceptionHandler;
use Mine\Framework\Middleware\CorsMiddleware;
use Mine\Framework\Middleware\ResponseMiddleware;


class ConfigProvider
{
    public function __invoke(): array
    {
        $serviceMap = $this->register_service_map();

        return [
            'dependencies' => array_merge($serviceMap, [
            ]),
            'exceptions' => [
                'handler' => [
                    'http' => [
                        CommonExceptionHandler::class,
                        GuzzleRequestExceptionHandler::class,
                        ValidationExceptionHandler::class,
                        AuthExceptionHandler::class,
                        ErrorExceptionHandler::class
                    ],
                ],
            ],
            'middlewares' => [
                'http' => [
                    CorsMiddleware::class,
                    ResponseMiddleware::class,
                ],
            ],
            'commands' => [
            ],
            'listeners' => [
                \Hyperf\ExceptionHandler\Listener\ErrorExceptionHandler::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            
            // 'publish' => [
            //     [
            //         'id'          => 'framework',
            //         'description' => 'framework配置',
            //         'source'      => __DIR__ . '/../publish/framework.php',
            //         'destination' => BASE_PATH . '/config/autoload/framework.php',
            //     ],
                // [
                //     'id'          => 'dependencies',
                //     'description' => '依赖配置',
                //     'source'      => __DIR__ . '/../publish/dependencies.php',
                //     'destination' => BASE_PATH . '/config/autoload/dependencies.php',
                // ],
            // ],
        ];
    }

    /**

   
    /**
     * 模型服务与契约的依赖配置.
     * @param string $path 契约与服务的相对路径
     * @return array 依赖数据
     */
   protected function service_map(string $path, string $namespacePrefix): array
    {
        //   var_dump($path);
        //   var_dump($namespacePrefix);
        $namespacePrefix = ltrim($namespacePrefix, '\\');
        $services = readFileName($path . '/Service');
        // var_dump($services);
        // exit;
        $dependencies = [];
        foreach ($services as $service) {
            $contractFilename = str_replace('Service', 'Contract', $service);
            $dependencies[$namespacePrefix . '\\Contract\\'.$contractFilename] = $namespacePrefix . '\\Service\\' . $service;
        }
        // echo "<pre>";
//         print_r($dependencies);
        return $dependencies;
    }




    /**
     * 模型服务与契约的依赖配置.
     * @param string $path 契约与服务的相对路径
     * @return array 依赖数据
     */
    protected function register_service_map(string $path="app", string $namespacePrefix = 'App'): array
    {
        $dependencies = [];
        if (! is_dir($path)) {
            return $dependencies;
        }
        
        $files = scandir(BASE_PATH . '/' . $path);

        foreach ($files as $file) {
            if (in_array($file, ['.', '..', '.DS_Store'])) {
                continue;
            }

            $dependencies = array_merge($this->service_map($path."/".$file, $namespacePrefix."\\".$file), $dependencies);

        }
//         var_dump($dependencies);
//         exit;
        return $dependencies;
    }

}
