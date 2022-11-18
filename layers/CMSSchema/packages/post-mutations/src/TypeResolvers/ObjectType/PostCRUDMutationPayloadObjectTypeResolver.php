<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\ObjectType;

use PoPSchema\PostMutations\RelationalTypeDataLoaders\ObjectType\PostCRUDMutationPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractTransientEntityPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostCRUDMutationPayloadObjectTypeResolver extends AbstractTransientEntityPayloadObjectTypeResolver
{
    private ?PostCRUDMutationPayloadObjectTypeDataLoader $postCRUDMutationPayloadObjectTypeDataLoader = null;

    final public function setPostCRUDMutationPayloadObjectTypeDataLoader(PostCRUDMutationPayloadObjectTypeDataLoader $postCRUDMutationPayloadObjectTypeDataLoader): void
    {
        $this->postCRUDMutationPayloadObjectTypeDataLoader = $postCRUDMutationPayloadObjectTypeDataLoader;
    }
    final protected function getPostCRUDMutationPayloadObjectTypeDataLoader(): PostCRUDMutationPayloadObjectTypeDataLoader
    {
        /** @var PostCRUDMutationPayloadObjectTypeDataLoader */
        return $this->postCRUDMutationPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(PostCRUDMutationPayloadObjectTypeDataLoader::class);
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
        return $this->getPostCRUDMutationPayloadObjectTypeDataLoader();
    }
}
