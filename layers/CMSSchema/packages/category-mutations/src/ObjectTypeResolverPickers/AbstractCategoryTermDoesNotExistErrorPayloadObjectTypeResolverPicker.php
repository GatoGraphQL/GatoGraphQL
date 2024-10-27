<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\ObjectModels\CategoryTermDoesNotExistErrorPayload;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\CategoryTermDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCategoryTermDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CategoryTermDoesNotExistErrorPayloadObjectTypeResolver $categoryDoesNotExistErrorPayloadObjectTypeResolver = null;

    final protected function getCategoryTermDoesNotExistErrorPayloadObjectTypeResolver(): CategoryTermDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->categoryDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var CategoryTermDoesNotExistErrorPayloadObjectTypeResolver */
            $categoryDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CategoryTermDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->categoryDoesNotExistErrorPayloadObjectTypeResolver = $categoryDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->categoryDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCategoryTermDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CategoryTermDoesNotExistErrorPayload::class;
    }
}
