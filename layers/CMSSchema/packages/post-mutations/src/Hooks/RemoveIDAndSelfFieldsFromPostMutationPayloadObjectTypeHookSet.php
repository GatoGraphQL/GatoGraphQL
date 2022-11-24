<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\Hooks;

use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\AbstractPostMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\Hooks\AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RemoveIDAndSelfFieldsFromPostMutationPayloadObjectTypeHookSet extends AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet
{
    /**
     * @phpstan-return class-string<ObjectTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    protected function getObjectTypeOrInterfaceTypeResolverClass(): string
    {
        return AbstractPostMutationPayloadObjectTypeResolver::class;
    }
}
