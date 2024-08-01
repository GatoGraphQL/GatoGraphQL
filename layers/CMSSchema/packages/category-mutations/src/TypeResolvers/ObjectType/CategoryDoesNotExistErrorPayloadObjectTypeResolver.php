<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\ObjectType\CategoryDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CategoryDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CategoryDoesNotExistErrorPayloadObjectTypeDataLoader $categoryDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final public function setCategoryDoesNotExistErrorPayloadObjectTypeDataLoader(CategoryDoesNotExistErrorPayloadObjectTypeDataLoader $categoryDoesNotExistErrorPayloadObjectTypeDataLoader): void
    {
        $this->categoryDoesNotExistErrorPayloadObjectTypeDataLoader = $categoryDoesNotExistErrorPayloadObjectTypeDataLoader;
    }
    final protected function getCategoryDoesNotExistErrorPayloadObjectTypeDataLoader(): CategoryDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->categoryDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var CategoryDoesNotExistErrorPayloadObjectTypeDataLoader */
            $categoryDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CategoryDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->categoryDoesNotExistErrorPayloadObjectTypeDataLoader = $categoryDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->categoryDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CategoryDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The requested category does not exist"', 'category-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCategoryDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
