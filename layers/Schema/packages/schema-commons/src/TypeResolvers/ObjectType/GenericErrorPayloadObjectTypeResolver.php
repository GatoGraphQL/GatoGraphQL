<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ObjectType;

use PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\GenericErrorPayloadObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?GenericErrorPayloadObjectTypeDataLoader $genericErrorPayloadObjectTypeDataLoader = null;

    final protected function getGenericErrorPayloadObjectTypeDataLoader(): GenericErrorPayloadObjectTypeDataLoader
    {
        if ($this->genericErrorPayloadObjectTypeDataLoader === null) {
            /** @var GenericErrorPayloadObjectTypeDataLoader */
            $genericErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(GenericErrorPayloadObjectTypeDataLoader::class);
            $this->genericErrorPayloadObjectTypeDataLoader = $genericErrorPayloadObjectTypeDataLoader;
        }
        return $this->genericErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Generic error payload', 'schema-commons');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericErrorPayloadObjectTypeDataLoader();
    }
}
