<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType\CannotDeleteYourselfErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CannotDeleteYourselfErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CannotDeleteYourselfErrorPayloadObjectTypeDataLoader $cannotDeleteYourselfErrorPayloadObjectTypeDataLoader = null;

    final protected function getCannotDeleteYourselfErrorPayloadObjectTypeDataLoader(): CannotDeleteYourselfErrorPayloadObjectTypeDataLoader
    {
        if ($this->cannotDeleteYourselfErrorPayloadObjectTypeDataLoader === null) {
            /** @var CannotDeleteYourselfErrorPayloadObjectTypeDataLoader */
            $cannotDeleteYourselfErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CannotDeleteYourselfErrorPayloadObjectTypeDataLoader::class);
            $this->cannotDeleteYourselfErrorPayloadObjectTypeDataLoader = $cannotDeleteYourselfErrorPayloadObjectTypeDataLoader;
        }
        return $this->cannotDeleteYourselfErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CannotDeleteYourselfErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user cannot delete themselves"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCannotDeleteYourselfErrorPayloadObjectTypeDataLoader();
    }
}
