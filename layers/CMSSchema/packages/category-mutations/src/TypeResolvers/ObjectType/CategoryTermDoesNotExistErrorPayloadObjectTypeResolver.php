<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\ObjectType\CategoryTermDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CategoryTermDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CategoryTermDoesNotExistErrorPayloadObjectTypeDataLoader $categoryDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getCategoryTermDoesNotExistErrorPayloadObjectTypeDataLoader(): CategoryTermDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->categoryDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var CategoryTermDoesNotExistErrorPayloadObjectTypeDataLoader */
            $categoryDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CategoryTermDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->categoryDoesNotExistErrorPayloadObjectTypeDataLoader = $categoryDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->categoryDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CategoryTermDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The requested category does not exist"', 'category-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCategoryTermDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
