<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\AbstractGenericCategoryMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCategoryMutationTransientEntityOperationPayloadObjectTypeFieldResolver extends AbstractTransientEntityOperationPayloadObjectTypeFieldResolver
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
            AbstractGenericCategoryMutationPayloadObjectTypeResolver::class,
        ];
    }
}
