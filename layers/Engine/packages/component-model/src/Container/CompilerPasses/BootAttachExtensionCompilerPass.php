<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\InterfaceTypeFieldResolvers\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface;
use PoP\Root\Component\ApplicationEvents;

class BootAttachExtensionCompilerPass extends AbstractAttachExtensionCompilerPass
{
    protected function getAttachExtensionEvent(): string
    {
        return ApplicationEvents::BOOT;
    }

    /**
     * @return array<string,string>
     */
    protected function getAttachableClassGroups(): array
    {
        return [
            ObjectTypeFieldResolverInterface::class => AttachableExtensionGroups::FIELDRESOLVERS,
            InterfaceTypeFieldResolverInterface::class => AttachableExtensionGroups::FIELDINTERFACERESOLVERS,
            DirectiveResolverInterface::class => AttachableExtensionGroups::DIRECTIVERESOLVERS,
            ObjectTypeResolverPickerInterface::class => AttachableExtensionGroups::TYPERESOLVERPICKERS,
        ];
    }
}
