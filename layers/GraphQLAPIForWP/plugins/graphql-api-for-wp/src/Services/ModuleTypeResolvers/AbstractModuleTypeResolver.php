<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\PluginAppGraphQLServerNames;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractModuleTypeResolver implements ModuleTypeResolverInterface
{
    use BasicServiceTrait;

    /**
     * Only initialize once, for the main AppThread
     */
    public function isServiceEnabled(): bool
    {
        return App::getAppThread()->getName() === PluginAppGraphQLServerNames::EXTERNAL;
    }

    /**
     * By default, the slug is the module's name, without the owner/package
     */
    public function getSlug(string $moduleType): string
    {
        $pos = strrpos($moduleType, '\\');
        if ($pos !== false) {
            return substr($moduleType, $pos + strlen('\\'));
        }
        return $moduleType;
    }

    /**
     * Provide a default name, just in case none is provided
     */
    public function getName(string $moduleType): string
    {
        return $this->getSlug($moduleType);
    }
}
