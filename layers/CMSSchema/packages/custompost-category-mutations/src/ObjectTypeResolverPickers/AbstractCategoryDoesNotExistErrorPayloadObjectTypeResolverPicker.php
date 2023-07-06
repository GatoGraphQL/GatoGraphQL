<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostCategoryMutations\ObjectModels\CategoryDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\CategoryDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCategoryDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CategoryDoesNotExistErrorPayloadObjectTypeResolver $categoryDoesNotExistErrorPayloadObjectTypeResolver = null;

    final public function setCategoryDoesNotExistErrorPayloadObjectTypeResolver(CategoryDoesNotExistErrorPayloadObjectTypeResolver $categoryDoesNotExistErrorPayloadObjectTypeResolver): void
    {
        $this->categoryDoesNotExistErrorPayloadObjectTypeResolver = $categoryDoesNotExistErrorPayloadObjectTypeResolver;
    }
    final protected function getCategoryDoesNotExistErrorPayloadObjectTypeResolver(): CategoryDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->categoryDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var CategoryDoesNotExistErrorPayloadObjectTypeResolver */
            $categoryDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CategoryDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->categoryDoesNotExistErrorPayloadObjectTypeResolver = $categoryDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->categoryDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCategoryDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CategoryDoesNotExistErrorPayload::class;
    }
}
