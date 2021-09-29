<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers\ObjectType;

use PoPSchema\Categories\FieldResolvers\ObjectType\AbstractCategoryObjectTypeFieldResolver;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;

class PostCategoryObjectTypeFieldResolver extends AbstractCategoryObjectTypeFieldResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostCategoryObjectTypeResolver::class,
        ];
    }
}
