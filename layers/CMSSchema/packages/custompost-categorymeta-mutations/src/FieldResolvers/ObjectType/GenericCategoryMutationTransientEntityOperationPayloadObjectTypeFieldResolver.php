<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategoryUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
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
            GenericCategoryUpdateMutationPayloadObjectTypeResolver::class,
            RootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver::class,
            RootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }
}
