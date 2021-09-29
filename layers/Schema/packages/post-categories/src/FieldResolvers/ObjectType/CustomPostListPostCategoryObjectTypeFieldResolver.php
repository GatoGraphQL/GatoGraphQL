<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers\ObjectType;

use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPSchema\Categories\FieldResolvers\ObjectType\AbstractCustomPostListCategoryObjectTypeFieldResolver;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;

class CustomPostListPostCategoryObjectTypeFieldResolver extends AbstractCustomPostListCategoryObjectTypeFieldResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function isServiceEnabled(): bool
    {
        /**
         * @todo Enable if the post category (i.e. taxonomy "category") can have other custom post types use it (eg: page, event, etc)
         */
        return false;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostCategoryObjectTypeResolver::class,
        ];
    }

    protected function getQueryProperty(): string
    {
        return 'category-ids';
    }
}
