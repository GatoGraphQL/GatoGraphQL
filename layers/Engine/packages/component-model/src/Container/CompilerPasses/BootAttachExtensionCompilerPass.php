<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface;
use PoP\Root\Module\ApplicationEvents;

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
            ObjectTypeFieldResolverInterface::class => AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS,
            InterfaceTypeFieldResolverInterface::class => AttachableExtensionGroups::INTERFACE_TYPE_FIELD_RESOLVERS,
            FieldDirectiveResolverInterface::class => AttachableExtensionGroups::DIRECTIVE_RESOLVERS,
            ObjectTypeResolverPickerInterface::class => AttachableExtensionGroups::OBJECT_TYPE_RESOLVER_PICKERS,
        ];
    }
}
