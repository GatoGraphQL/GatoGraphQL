<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Comments\RelationalTypeDataLoaders\ObjectType\CommentTypeDataLoader;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;

class CommentObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?CommentTypeDataLoader $commentTypeDataLoader = null;

    final public function setCommentTypeAPI(CommentTypeAPIInterface $commentTypeAPI): void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        return $this->commentTypeAPI ??= $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
    }
    final public function setCommentTypeDataLoader(CommentTypeDataLoader $commentTypeDataLoader): void
    {
        $this->commentTypeDataLoader = $commentTypeDataLoader;
    }
    final protected function getCommentTypeDataLoader(): CommentTypeDataLoader
    {
        return $this->commentTypeDataLoader ??= $this->instanceManager->getInstance(CommentTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'Comment';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Comments added to custom posts', 'comments');
    }

    public function getID(object $object): string | int | null
    {
        $comment = $object;
        return $this->getCommentTypeAPI()->getCommentId($comment);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentTypeDataLoader();
    }
}
