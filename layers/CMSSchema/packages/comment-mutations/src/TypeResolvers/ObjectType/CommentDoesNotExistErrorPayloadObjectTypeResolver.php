<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentDoesNotExistErrorPayloadObjectTypeDataLoader $commentDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getCommentDoesNotExistErrorPayloadObjectTypeDataLoader(): CommentDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->commentDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var CommentDoesNotExistErrorPayloadObjectTypeDataLoader */
            $commentDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CommentDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->commentDoesNotExistErrorPayloadObjectTypeDataLoader = $commentDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->commentDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The comment does not exist"', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
