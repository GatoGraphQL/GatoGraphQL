<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers;

use PoPSchema\PostCategories\TypeResolvers\PostCategoryTypeResolver;
use PoPSchema\Categories\FieldResolvers\AbstractCustomPostListCategoryFieldResolver;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;

class CustomPostListPostCategoryFieldResolver extends AbstractCustomPostListCategoryFieldResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function getClassesToAttachTo(): array
    {
        return array(PostCategoryTypeResolver::class);
    }

    protected function getQueryProperty(): string
    {
        return 'category-ids';
    }
}
