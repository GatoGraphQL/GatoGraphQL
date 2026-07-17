<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\ObjectType\CommentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CommentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader $commentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader = null;

    final protected function getCommentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader(): CommentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader
    {
        if ($this->commentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader === null) {
            /** @var CommentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader */
            $commentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CommentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader::class);
            $this->commentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader = $commentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader;
        }
        return $this->commentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentHasAlreadyBeenTrashedErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The comment has already been sent to the trash"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader();
    }
}
