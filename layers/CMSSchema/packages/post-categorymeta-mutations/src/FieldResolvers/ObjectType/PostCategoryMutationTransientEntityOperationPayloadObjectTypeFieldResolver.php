<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\AbstractPostCategoryMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostCategoryMutationTransientEntityOperationPayloadObjectTypeFieldResolver extends AbstractTransientEntityOperationPayloadObjectTypeFieldResolver
{
    protected function getObjectIDFieldName(): string
    {
        return 'categoryID';
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractPostCategoryMetaMutationPayloadObjectTypeResolver::class,
        ];
    }
}
