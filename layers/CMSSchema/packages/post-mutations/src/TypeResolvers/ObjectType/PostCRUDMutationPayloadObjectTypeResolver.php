<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\ObjectType;

use PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\ObjectMutationPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostCRUDMutationPayloadObjectTypeResolver extends AbstractTransientEntityOperationPayloadObjectTypeResolver
{
    private ?ObjectMutationPayloadObjectTypeDataLoader $mutationPayloadObjectTypeDataLoader = null;

    final public function setTransientEntityOperationPayloadObjectTypeDataLoader(ObjectMutationPayloadObjectTypeDataLoader $mutationPayloadObjectTypeDataLoader): void
    {
        $this->mutationPayloadObjectTypeDataLoader = $mutationPayloadObjectTypeDataLoader;
    }
    final protected function getTransientEntityOperationPayloadObjectTypeDataLoader(): ObjectMutationPayloadObjectTypeDataLoader
    {
        /** @var ObjectMutationPayloadObjectTypeDataLoader */
        return $this->mutationPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(ObjectMutationPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'PostCRUDMutationPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Payload of executing a CRUD mutation involving a post', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getTransientEntityOperationPayloadObjectTypeDataLoader();
    }
}
