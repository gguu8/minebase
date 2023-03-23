<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Mine\Framework\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Utils\Str;
use Psr\Container\ContainerInterface;

#[Command]
class ServiceInterfaceCommand extends HyperfCommand
{
    use CommandTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('mcGen:serviceInterface');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('mochat - 生成service, 默认生成于 app/Contract 目录下');
        $this->configureTrait();
    }

    public function handle()
    {
        ## 获取配置
        [$models, $path] = $this->stubConfig();

        $this->createInterface($models, $path);
    }

    /**
     * 根据模型 创建服务契约.
     * @param array $models 模型名称
     * @param string $modelPath 模型路径
     */
    protected function createInterface(array $models, string $modelPath): void
    {
        $interfaceSpace = ucfirst(str_replace(['/', 'Model'], ['\\', 'Contract'], $modelPath));
        $interfacePath  = str_replace('Model', 'Contract', $modelPath);

        $stub = file_get_contents(__DIR__ . '/stubs/ServiceInterface.stub');

        foreach ($models as $model) {
            $interface   = $model . 'ServiceInterface';
            $serviceFile = BASE_PATH . '/' . $interfacePath . '/' . $interface . '.php';
            $fileContent = str_replace(
                ['#INTERFACE#', '#INTERFACE_NAMESPACE#', '#MODEL#', '#MODEL_PLURA#'],
                [$interface, $interfaceSpace, $model, Str::plural($model)],
                $stub
            );
            $this->doTouch($serviceFile, $fileContent);
        }
    }
}
