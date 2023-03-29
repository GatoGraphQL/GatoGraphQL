<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Comments\RelationalTypeDataLoaders\ObjectType\CommentObjectTypeDataLoader;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;

class CommentObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?CommentObjectTypeDataLoader $commentObjectTypeDataLoader = null;

    final public function setCommentTypeAPI(CommentTypeAPIInterface $commentTypeAPI): void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        /** @var CommentTypeAPIInterface */
        return $this->commentTypeAPI ??= $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
    }
    final public function setCommentObjectTypeDataLoader(CommentObjectTypeDataLoader $commentObjectTypeDataLoader): void
    {
        $this->commentObjectTypeDataLoader = $commentObjectTypeDataLoader;
    }
    final protected function getCommentObjectTypeDataLoader(): CommentObjectTypeDataLoader
    {
        /** @var CommentObjectTypeDataLoader */
        return $this->commentObjectTypeDataLoader ??= $this->instanceManager->getInstance(CommentObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'Comment';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Comments added to custom posts', 'comments');
    }

    public function getID(object $object): string|int|null
    {
        $comment = $object;
        return $this->getCommentTypeAPI()->getCommentID($comment);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentObjectTypeDataLoader();
    }
}
