<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleTypeResolvers;

use GraphQLAPI\GraphQLAPI\ModuleTypeResolvers\ModuleTypeResolverInterface;

abstract class AbstractModuleTypeResolver implements ModuleTypeResolverInterface
{
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
