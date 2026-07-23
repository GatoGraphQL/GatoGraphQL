<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType\InvalidEmailErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class InvalidEmailErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?InvalidEmailErrorPayloadObjectTypeDataLoader $invalidEmailErrorPayloadObjectTypeDataLoader = null;

    final protected function getInvalidEmailErrorPayloadObjectTypeDataLoader(): InvalidEmailErrorPayloadObjectTypeDataLoader
    {
        if ($this->invalidEmailErrorPayloadObjectTypeDataLoader === null) {
            /** @var InvalidEmailErrorPayloadObjectTypeDataLoader */
            $invalidEmailErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(InvalidEmailErrorPayloadObjectTypeDataLoader::class);
            $this->invalidEmailErrorPayloadObjectTypeDataLoader = $invalidEmailErrorPayloadObjectTypeDataLoader;
        }
        return $this->invalidEmailErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'InvalidEmailErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The email is invalid"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getInvalidEmailErrorPayloadObjectTypeDataLoader();
    }
}
