<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Scripts;

use GraphQLAPI\GraphQLAPI\PluginApp;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;

trait ExtensionScriptTrait
{
    /**
     * @phpstan-return class-string<ExtensionInterface>
     */
    abstract protected function getExtensionClass(): string;

    protected function getPluginDir(): string
    {
        return PluginApp::getExtension($this->getExtensionClass())->getPluginDir();
    }

    protected function getPluginURL(): string
    {
        // Remove the trailing slash
        return trim(PluginApp::getExtension($this->getExtensionClass())->getPluginURL(), '/');
    }
}
