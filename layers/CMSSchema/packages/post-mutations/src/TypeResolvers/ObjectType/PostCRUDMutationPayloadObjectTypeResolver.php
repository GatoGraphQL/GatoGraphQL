<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\ObjectType;

use PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\TransientEntityPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractTransientEntityPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostCRUDMutationPayloadObjectTypeResolver extends AbstractTransientEntityPayloadObjectTypeResolver
{
    private ?TransientEntityPayloadObjectTypeDataLoader $transientEntityPayloadObjectTypeDataLoader = null;

    final public function setTransientEntityPayloadObjectTypeDataLoader(TransientEntityPayloadObjectTypeDataLoader $transientEntityPayloadObjectTypeDataLoader): void
    {
        $this->transientEntityPayloadObjectTypeDataLoader = $transientEntityPayloadObjectTypeDataLoader;
    }
    final protected function getTransientEntityPayloadObjectTypeDataLoader(): TransientEntityPayloadObjectTypeDataLoader
    {
        /** @var TransientEntityPayloadObjectTypeDataLoader */
        return $this->transientEntityPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(TransientEntityPayloadObjectTypeDataLoader::class);
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
