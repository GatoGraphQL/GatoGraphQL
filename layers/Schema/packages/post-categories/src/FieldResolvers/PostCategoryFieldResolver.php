<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers;

use PoPSchema\PostCategories\TypeResolvers\Object\PostCategoryTypeResolver;
use PoPSchema\Categories\FieldResolvers\AbstractCategoryFieldResolver;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;

class PostCategoryFieldResolver extends AbstractCategoryFieldResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function getClassesToAttachTo(): array
    {
        return array(PostCategoryTypeResolver::class);
    }
}
