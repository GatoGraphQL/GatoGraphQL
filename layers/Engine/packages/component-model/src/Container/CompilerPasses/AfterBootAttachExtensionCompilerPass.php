<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\RelationalTypeResolverDecorators\RelationalTypeResolverDecoratorInterface;
use PoP\Root\Module\ApplicationEvents;

class AfterBootAttachExtensionCompilerPass extends AbstractAttachExtensionCompilerPass
{
    protected function getAttachExtensionEvent(): string
    {
        return ApplicationEvents::AFTER_BOOT;
    }

    /**
     * @return array<string,string>
     */
    protected function getAttachableClassGroups(): array
    {
        return [
            RelationalTypeResolverDecoratorInterface::class => AttachableExtensionGroups::RELATIONAL_TYPE_RESOLVER_DECORATORS,
        ];
    }
}
