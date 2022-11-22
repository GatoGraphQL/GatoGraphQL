<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\ObjectType;

use PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\MutationPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostCRUDMutationPayloadObjectTypeResolver extends AbstractTransientEntityOperationPayloadObjectTypeResolver
{
    private ?MutationPayloadObjectTypeDataLoader $mutationPayloadObjectTypeDataLoader = null;

    final public function setTransientEntityOperationPayloadObjectTypeDataLoader(MutationPayloadObjectTypeDataLoader $mutationPayloadObjectTypeDataLoader): void
    {
        $this->mutationPayloadObjectTypeDataLoader = $mutationPayloadObjectTypeDataLoader;
    }
    final protected function getTransientEntityOperationPayloadObjectTypeDataLoader(): MutationPayloadObjectTypeDataLoader
    {
        /** @var MutationPayloadObjectTypeDataLoader */
        return $this->mutationPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(MutationPayloadObjectTypeDataLoader::class);
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
