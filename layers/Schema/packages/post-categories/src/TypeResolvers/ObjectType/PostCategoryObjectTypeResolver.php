<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoPSchema\PostCategories\RelationalTypeDataLoaders\ObjectType\PostCategoryTypeDataLoader;
use PoPSchema\Categories\TypeResolvers\ObjectType\AbstractCategoryObjectTypeResolver;

class PostCategoryObjectTypeResolver extends AbstractCategoryObjectTypeResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    protected PostCategoryTypeDataLoader $postCategoryTypeDataLoader;

    #[Required]
    public function autowirePostCategoryObjectTypeResolver(
        PostCategoryTypeDataLoader $postCategoryTypeDataLoader,
    ): void {
        $this->postCategoryTypeDataLoader = $postCategoryTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostCategory';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a category, added to a post', 'post-categories');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->postCategoryTypeDataLoader;
    }
}
