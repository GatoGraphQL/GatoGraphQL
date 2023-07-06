<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\RelationalTypeDataLoaders\ObjectType\TagDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class TagDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?TagDoesNotExistErrorPayloadObjectTypeDataLoader $customPostDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final public function setTagDoesNotExistErrorPayloadObjectTypeDataLoader(TagDoesNotExistErrorPayloadObjectTypeDataLoader $customPostDoesNotExistErrorPayloadObjectTypeDataLoader): void
    {
        $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
    }
    final protected function getTagDoesNotExistErrorPayloadObjectTypeDataLoader(): TagDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var TagDoesNotExistErrorPayloadObjectTypeDataLoader */
            $customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(TagDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'TagDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The requested tag does not exist"', 'custompost-tag-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getTagDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
