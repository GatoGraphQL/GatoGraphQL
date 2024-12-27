<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers;

use PoP\Root\Services\AbstractBasicService;

abstract class AbstractModuleTypeResolver extends AbstractBasicService implements ModuleTypeResolverInterface
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

    public function isHidden(string $moduleType): bool
    {
        return false;
    }
}
