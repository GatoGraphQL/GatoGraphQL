<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\ObjectType;

use PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\ObjectMutationPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

abstract class AbstractPostMutationPayloadObjectTypeResolver extends AbstractTransientEntityOperationPayloadObjectTypeResolver
{
    private ?ObjectMutationPayloadObjectTypeDataLoader $objectMutationPayloadObjectTypeDataLoader = null;

    final public function setObjectMutationPayloadObjectTypeDataLoader(ObjectMutationPayloadObjectTypeDataLoader $objectMutationPayloadObjectTypeDataLoader): void
    {
        $this->objectMutationPayloadObjectTypeDataLoader = $objectMutationPayloadObjectTypeDataLoader;
    }
    final protected function getObjectMutationPayloadObjectTypeDataLoader(): ObjectMutationPayloadObjectTypeDataLoader
    {
        /** @var ObjectMutationPayloadObjectTypeDataLoader */
        return $this->objectMutationPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(ObjectMutationPayloadObjectTypeDataLoader::class);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getObjectMutationPayloadObjectTypeDataLoader();
    }
}
