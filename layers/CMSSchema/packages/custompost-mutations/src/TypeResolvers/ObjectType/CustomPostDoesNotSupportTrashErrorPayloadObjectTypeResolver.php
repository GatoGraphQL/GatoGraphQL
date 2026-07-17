<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\CustomPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostDoesNotSupportTrashErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CustomPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader $customPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader = null;

    final protected function getCustomPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader(): CustomPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader
    {
        if ($this->customPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader === null) {
            /** @var CustomPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader */
            $customPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CustomPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader::class);
            $this->customPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader = $customPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader;
        }
        return $this->customPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPostDoesNotSupportTrashErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The custom post does not support being sent to the trash"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostDoesNotSupportTrashErrorPayloadObjectTypeDataLoader();
    }
}
