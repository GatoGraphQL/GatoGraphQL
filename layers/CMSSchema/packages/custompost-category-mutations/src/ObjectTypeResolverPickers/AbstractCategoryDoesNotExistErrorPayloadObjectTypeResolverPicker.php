<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostCategoryMutations\ObjectModels\CategoryDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\CategoryDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCategoryDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CategoryDoesNotExistErrorPayloadObjectTypeResolver $mediaItemDoesNotExistErrorPayloadObjectTypeResolver = null;

    final public function setCategoryDoesNotExistErrorPayloadObjectTypeResolver(CategoryDoesNotExistErrorPayloadObjectTypeResolver $mediaItemDoesNotExistErrorPayloadObjectTypeResolver): void
    {
        $this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver = $mediaItemDoesNotExistErrorPayloadObjectTypeResolver;
    }
    final protected function getCategoryDoesNotExistErrorPayloadObjectTypeResolver(): CategoryDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var CategoryDoesNotExistErrorPayloadObjectTypeResolver */
            $mediaItemDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CategoryDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver = $mediaItemDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver;
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
