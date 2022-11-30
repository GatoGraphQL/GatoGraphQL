<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\Hooks;

use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\AbstractPostTagsMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\Hooks\AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RemoveIDAndSelfFieldsFromPostTagsMutationPayloadObjectTypeHookSet extends AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet
{
    /**
     * @phpstan-return class-string<ObjectTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    protected function getObjectTypeOrInterfaceTypeResolverClass(): string
    {
        return AbstractPostTagsMutationPayloadObjectTypeResolver::class;
    }
}
