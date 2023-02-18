<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Mine\Framework\Event;

class PluginEvent
{
    /**
     * @var null|string|string[]
     */
    protected $package;

    /**
     * @var null|string|string[]
     */
    protected $version;

    public function __construct(array $config)
    {
        [$this->package, $this->version] = $config;
    }

    /**
     * 获取插件包名称.
     * @return string ...
     */
    public function getPackage(): string
    {
        return $this->package;
    }

    /**
     * 获取插件包版本.
     * @return string ...
     */
    public function getVersion(): string
    {
        return $this->package;
    }
}
