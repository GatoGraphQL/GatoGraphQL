<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPSchema\Categories\TypeResolvers\ObjectType\AbstractCategoryObjectTypeResolver;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoPSchema\PostCategories\RelationalTypeDataLoaders\ObjectType\PostCategoryTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class PostCategoryObjectTypeResolver extends AbstractCategoryObjectTypeResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    private ?PostCategoryTypeDataLoader $postCategoryTypeDataLoader = null;

    public function setPostCategoryTypeDataLoader(PostCategoryTypeDataLoader $postCategoryTypeDataLoader): void
    {
        $this->postCategoryTypeDataLoader = $postCategoryTypeDataLoader;
    }
    protected function getPostCategoryTypeDataLoader(): PostCategoryTypeDataLoader
    {
        return $this->postCategoryTypeDataLoader ??= $this->instanceManager->getInstance(PostCategoryTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'PostCategory';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Representation of a category, added to a post', 'post-categories');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostCategoryTypeDataLoader();
    }
}
