<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader $customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader = null;

    final protected function getCustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader(): CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader
    {
        if ($this->customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader === null) {
            /** @var CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader */
            $customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader::class);
            $this->customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader = $customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader;
        }
        return $this->customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPostDoesNotHaveExpectedTypeErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The requested custom post does not have the expected type"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeDataLoader();
    }
}
