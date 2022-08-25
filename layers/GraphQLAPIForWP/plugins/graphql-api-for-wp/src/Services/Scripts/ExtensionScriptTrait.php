<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Scripts;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;

trait ExtensionScriptTrait
{
    /**
     * @phpstan-return class-string<ExtensionInterface>
     */
    abstract protected function getExtensionClass(): string;

    protected function getPluginDir(): string
    {
        return App::getExtension($this->getExtensionClass())->getPluginDir();
    }

    protected function getPluginURL(): string
    {
        // Remove the trailing slash
        return trim(App::getExtension($this->getExtensionClass())->getPluginURL(), '/');
    }
}
