<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\Hooks;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\AbstractPostCategoriesMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\Hooks\AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RemoveIDAndSelfFieldsFromPostCategoriesMutationPayloadObjectTypeHookSet extends AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet
{
    /**
     * @phpstan-return class-string<ObjectTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    protected function getObjectTypeOrInterfaceTypeResolverClass(): string
    {
        return AbstractPostCategoriesMutationPayloadObjectTypeResolver::class;
    }
}
