<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TagMutations\ObjectModels\TagTermDoesNotExistErrorPayload;
use PoPCMSSchema\TagMutations\TypeResolvers\ObjectType\TagTermDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractTagTermDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?TagTermDoesNotExistErrorPayloadObjectTypeResolver $tagDoesNotExistErrorPayloadObjectTypeResolver = null;

    final public function setTagTermDoesNotExistErrorPayloadObjectTypeResolver(TagTermDoesNotExistErrorPayloadObjectTypeResolver $tagDoesNotExistErrorPayloadObjectTypeResolver): void
    {
        $this->tagDoesNotExistErrorPayloadObjectTypeResolver = $tagDoesNotExistErrorPayloadObjectTypeResolver;
    }
    final protected function getTagTermDoesNotExistErrorPayloadObjectTypeResolver(): TagTermDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->tagDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var TagTermDoesNotExistErrorPayloadObjectTypeResolver */
            $tagDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(TagTermDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->tagDoesNotExistErrorPayloadObjectTypeResolver = $tagDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->tagDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getTagTermDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return TagTermDoesNotExistErrorPayload::class;
    }
}
