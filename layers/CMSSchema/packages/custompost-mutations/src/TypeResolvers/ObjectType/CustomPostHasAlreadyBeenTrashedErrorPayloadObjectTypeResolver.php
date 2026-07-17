<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\CustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader $customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader = null;

    final protected function getCustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader(): CustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader
    {
        if ($this->customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader === null) {
            /** @var CustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader */
            $customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader::class);
            $this->customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader = $customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader;
        }
        return $this->customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPostHasAlreadyBeenTrashedErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The custom post has already been sent to the trash"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader();
    }
}
