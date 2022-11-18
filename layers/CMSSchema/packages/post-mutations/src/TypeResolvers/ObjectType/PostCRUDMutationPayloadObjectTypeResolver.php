<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\ObjectType;

use PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\MutationPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractTransientEntityPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostCRUDMutationPayloadObjectTypeResolver extends AbstractTransientEntityPayloadObjectTypeResolver
{
    private ?MutationPayloadObjectTypeDataLoader $mutationPayloadObjectTypeDataLoader = null;

    final public function setTransientEntityPayloadObjectTypeDataLoader(MutationPayloadObjectTypeDataLoader $mutationPayloadObjectTypeDataLoader): void
    {
        $this->mutationPayloadObjectTypeDataLoader = $mutationPayloadObjectTypeDataLoader;
    }
    final protected function getTransientEntityPayloadObjectTypeDataLoader(): MutationPayloadObjectTypeDataLoader
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
        return $this->getTransientEntityPayloadObjectTypeDataLoader();
    }
}
