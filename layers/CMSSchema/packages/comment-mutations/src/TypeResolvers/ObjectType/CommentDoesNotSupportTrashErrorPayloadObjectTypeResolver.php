<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentDoesNotSupportTrashErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader $commentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader = null;

    final protected function getCommentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader(): CommentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader
    {
        if ($this->commentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader === null) {
            /** @var CommentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader */
            $commentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CommentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader::class);
            $this->commentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader = $commentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader;
        }
        return $this->commentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentDoesNotSupportTrashErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The comment does not support being sent to the trash"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentDoesNotSupportTrashErrorPayloadObjectTypeDataLoader();
    }
}
