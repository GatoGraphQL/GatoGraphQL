<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers\ObjectType;

use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryTypeResolver;
use PoPSchema\Categories\FieldResolvers\ObjectType\AbstractCategoryObjectTypeFieldResolver;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;

class PostCategoryObjectTypeFieldResolver extends AbstractCategoryObjectTypeFieldResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostCategoryTypeResolver::class,
        ];
    }
}
