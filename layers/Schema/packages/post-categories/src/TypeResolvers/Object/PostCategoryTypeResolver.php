<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\TypeResolvers\ObjectType;

use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoPSchema\PostCategories\RelationalTypeDataLoaders\ObjectType\PostCategoryTypeDataLoader;
use PoPSchema\Categories\TypeResolvers\ObjectType\AbstractCategoryTypeResolver;

class PostCategoryTypeResolver extends AbstractCategoryTypeResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function getTypeName(): string
    {
        return 'PostCategory';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a category, added to a post', 'post-categories');
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return PostCategoryTypeDataLoader::class;
    }
}
