<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ObjectType;

use PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\ErrorPayloadObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractTransientObjectObjectTypeResolver;

class ErrorPayloadObjectTypeResolver extends AbstractTransientObjectObjectTypeResolver
{
    private ?ErrorPayloadObjectTypeDataLoader $errorPayloadObjectTypeDataLoader = null;

    final public function setErrorPayloadObjectTypeDataLoader(ErrorPayloadObjectTypeDataLoader $errorPayloadObjectTypeDataLoader): void
    {
        $this->errorPayloadObjectTypeDataLoader = $errorPayloadObjectTypeDataLoader;
    }
    final protected function getErrorPayloadObjectTypeDataLoader(): ErrorPayloadObjectTypeDataLoader
    {
        /** @var ErrorPayloadObjectTypeDataLoader */
        return $this->errorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(ErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'ErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload', 'schema-commons');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getErrorPayloadObjectTypeDataLoader();
    }
}
