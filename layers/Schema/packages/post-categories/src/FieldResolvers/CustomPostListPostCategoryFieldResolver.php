<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers;

use PoPSchema\PostCategories\TypeResolvers\Object\PostCategoryTypeResolver;
use PoPSchema\Categories\FieldResolvers\AbstractCustomPostListCategoryFieldResolver;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;

class CustomPostListPostCategoryFieldResolver extends AbstractCustomPostListCategoryFieldResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function isServiceEnabled(): bool
    {
        /**
         * @todo Enable if the post category (i.e. taxonomy "category") can have other custom post types use it (eg: page, event, etc)
         */
        return false;
    }

    public function getClassesToAttachTo(): array
    {
        return array(PostCategoryTypeResolver::class);
    }

    protected function getQueryProperty(): string
    {
        return 'category-ids';
    }
}
