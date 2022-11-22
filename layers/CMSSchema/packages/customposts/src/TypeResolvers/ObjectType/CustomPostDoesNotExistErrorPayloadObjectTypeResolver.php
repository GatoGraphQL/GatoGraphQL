<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader $customPostDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final public function setCustomPostDoesNotExistErrorPayloadObjectTypeDataLoader(CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader $customPostDoesNotExistErrorPayloadObjectTypeDataLoader): void
    {
        $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
    }
    final protected function getCustomPostDoesNotExistErrorPayloadObjectTypeDataLoader(): CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        /** @var CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader */
        return $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostDoesNotExistErrorPayloadObjectTypeDataLoader::class);
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
