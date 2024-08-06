<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TagMutations\ObjectModels\TagDoesNotExistErrorPayload;
use PoPCMSSchema\TagMutations\TypeResolvers\ObjectType\TagDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractTagDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?TagDoesNotExistErrorPayloadObjectTypeResolver $tagDoesNotExistErrorPayloadObjectTypeResolver = null;

    final public function setTagDoesNotExistErrorPayloadObjectTypeResolver(TagDoesNotExistErrorPayloadObjectTypeResolver $tagDoesNotExistErrorPayloadObjectTypeResolver): void
    {
        $this->tagDoesNotExistErrorPayloadObjectTypeResolver = $tagDoesNotExistErrorPayloadObjectTypeResolver;
    }
    final protected function getTagDoesNotExistErrorPayloadObjectTypeResolver(): TagDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->tagDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var TagDoesNotExistErrorPayloadObjectTypeResolver */
            $tagDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(TagDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->tagDoesNotExistErrorPayloadObjectTypeResolver = $tagDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->tagDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getTagDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return TagDoesNotExistErrorPayload::class;
    }
}
