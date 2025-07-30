<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostAncestorRecursionErrorPayload;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\CustomPostAncestorRecursionErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCustomPostAncestorRecursionErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CustomPostAncestorRecursionErrorPayloadObjectTypeResolver $customPostAncestorRecursionErrorPayloadObjectTypeResolver = null;

    final protected function getCustomPostAncestorRecursionErrorPayloadObjectTypeResolver(): CustomPostAncestorRecursionErrorPayloadObjectTypeResolver
    {
        if ($this->customPostAncestorRecursionErrorPayloadObjectTypeResolver === null) {
            /** @var CustomPostAncestorRecursionErrorPayloadObjectTypeResolver */
            $customPostAncestorRecursionErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CustomPostAncestorRecursionErrorPayloadObjectTypeResolver::class);
            $this->customPostAncestorRecursionErrorPayloadObjectTypeResolver = $customPostAncestorRecursionErrorPayloadObjectTypeResolver;
        }
        return $this->customPostAncestorRecursionErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCustomPostAncestorRecursionErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CustomPostAncestorRecursionErrorPayload::class;
    }
}
