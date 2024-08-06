<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\TagMutations\RelationalTypeDataLoaders\ObjectType\TagDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class TagDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?TagDoesNotExistErrorPayloadObjectTypeDataLoader $tagDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final public function setTagDoesNotExistErrorPayloadObjectTypeDataLoader(TagDoesNotExistErrorPayloadObjectTypeDataLoader $tagDoesNotExistErrorPayloadObjectTypeDataLoader): void
    {
        $this->tagDoesNotExistErrorPayloadObjectTypeDataLoader = $tagDoesNotExistErrorPayloadObjectTypeDataLoader;
    }
    final protected function getTagDoesNotExistErrorPayloadObjectTypeDataLoader(): TagDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->tagDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var TagDoesNotExistErrorPayloadObjectTypeDataLoader */
            $tagDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(TagDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->tagDoesNotExistErrorPayloadObjectTypeDataLoader = $tagDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->tagDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'TagDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The requested tag does not exist"', 'tag-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getTagDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
