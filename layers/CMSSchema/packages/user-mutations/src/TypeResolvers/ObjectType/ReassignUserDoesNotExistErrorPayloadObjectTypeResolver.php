<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType\ReassignUserDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class ReassignUserDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?ReassignUserDoesNotExistErrorPayloadObjectTypeDataLoader $reassignUserDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getReassignUserDoesNotExistErrorPayloadObjectTypeDataLoader(): ReassignUserDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->reassignUserDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var ReassignUserDoesNotExistErrorPayloadObjectTypeDataLoader */
            $reassignUserDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(ReassignUserDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->reassignUserDoesNotExistErrorPayloadObjectTypeDataLoader = $reassignUserDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->reassignUserDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'ReassignUserDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user to reassign the content to does not exist"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getReassignUserDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
