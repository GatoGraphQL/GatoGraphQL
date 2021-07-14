<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Scripts;

use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;

trait ExtensionScriptTrait
{
    abstract protected function getExtensionClass(): string;

    protected function getPluginDir(): string
    {
        return (string) ExtensionManager::getConfig(
            $this->getExtensionClass(),
            'dir'
        );
    }

    protected function getPluginURL(): string
    {
        // Remove the trailing slash
        return trim((string) ExtensionManager::getConfig(
            $this->getExtensionClass(),
            'url'
        ), '/');
    }
}
