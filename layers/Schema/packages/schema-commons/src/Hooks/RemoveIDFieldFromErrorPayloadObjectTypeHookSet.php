<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Hooks;

use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\ErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\Hooks\AbstractRemoveIDFieldFromObjectTypeHookSet;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RemoveIDFieldFromErrorPayloadObjectTypeHookSet extends AbstractRemoveIDFieldFromObjectTypeHookSet
{
    /**
     * @phpstan-return class-string<ObjectTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    protected function getObjectTypeOrInterfaceTypeResolverClass(): string
    {
        return ErrorPayloadObjectTypeResolver::class;
    }
}
