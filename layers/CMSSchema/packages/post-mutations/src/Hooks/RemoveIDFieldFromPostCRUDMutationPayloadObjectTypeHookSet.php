<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\Hooks;

use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\PostCRUDMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\Hooks\AbstractRemoveIDFieldFromObjectTypeHookSet;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RemoveIDFieldFromPostCRUDMutationPayloadObjectTypeHookSet extends AbstractRemoveIDFieldFromObjectTypeHookSet
{
    /**
     * @phpstan-return class-string<ObjectTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    protected function getObjectTypeOrInterfaceTypeResolverClass(): string
    {
        return PostCRUDMutationPayloadObjectTypeResolver::class;
    }
}
