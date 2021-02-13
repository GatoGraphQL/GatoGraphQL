<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Component\ApplicationEvents;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\TypeResolverPickers\TypeResolverPickerInterface;

class BeforeBootAttachExtensionCompilerPass extends AbstractAttachExtensionCompilerPass
{
    protected function getAttachExtensionEvent(): string
    {
        return ApplicationEvents::BEFORE_BOOT;
    }

    /**
     * @return array<string,string>
     */
    protected function getAttachableClassGroups(): array
    {
        return [
            FieldResolverInterface::class => AttachableExtensionGroups::FIELDRESOLVERS,
            DirectiveResolverInterface::class => AttachableExtensionGroups::DIRECTIVERESOLVERS,
            TypeResolverPickerInterface::class => AttachableExtensionGroups::TYPERESOLVERPICKERS,
        ];
    }
}
