<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\AbstractGenericCategoryMetaMutationPayloadObjectTypeResolver;
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
            AbstractGenericCategoryMetaMutationPayloadObjectTypeResolver::class,
        ];
    }
}
