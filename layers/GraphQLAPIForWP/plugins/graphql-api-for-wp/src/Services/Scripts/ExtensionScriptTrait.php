<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Scripts;

use GraphQLAPI\GraphQLAPI\App;

trait ExtensionScriptTrait
{
    abstract protected function getExtensionClass(): string;

    protected function getPluginDir(): string
    {
        return (string) App::getExtensionManager()->getConfig(
            $this->getExtensionClass(),
            'dir'
        );
    }

    protected function getPluginURL(): string
    {
        // Remove the trailing slash
        return trim((string) App::getExtensionManager()->getConfig(
            $this->getExtensionClass(),
            'url'
        ), '/');
    }
}
