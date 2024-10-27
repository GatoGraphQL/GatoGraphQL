<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader $customPostDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getCustomPostDoesNotExistErrorPayloadObjectTypeDataLoader(): CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader */
            $customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPostDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The requested custom post does not exist"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
