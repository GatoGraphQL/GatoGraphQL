<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ObjectType;

use PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\GenericErrorPayloadObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?GenericErrorPayloadObjectTypeDataLoader $genericErrorPayloadObjectTypeDataLoader = null;

    final public function setGenericErrorPayloadObjectTypeDataLoader(GenericErrorPayloadObjectTypeDataLoader $genericErrorPayloadObjectTypeDataLoader): void
    {
        $this->genericErrorPayloadObjectTypeDataLoader = $genericErrorPayloadObjectTypeDataLoader;
    }
    final protected function getGenericErrorPayloadObjectTypeDataLoader(): GenericErrorPayloadObjectTypeDataLoader
    {
        /** @var GenericErrorPayloadObjectTypeDataLoader */
        return $this->genericErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(GenericErrorPayloadObjectTypeDataLoader::class);
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
