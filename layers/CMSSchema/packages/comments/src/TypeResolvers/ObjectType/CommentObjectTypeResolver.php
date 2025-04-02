<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Comments\RelationalTypeDataLoaders\ObjectType\CommentObjectTypeDataLoader;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;

class CommentObjectTypeResolver extends AbstractObjectTypeResolver implements CommentObjectTypeResolverInterface
{
    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?CommentObjectTypeDataLoader $commentObjectTypeDataLoader = null;

    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        if ($this->commentTypeAPI === null) {
            /** @var CommentTypeAPIInterface */
            $commentTypeAPI = $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
            $this->commentTypeAPI = $commentTypeAPI;
        }
        return $this->commentTypeAPI;
    }
    final protected function getCommentObjectTypeDataLoader(): CommentObjectTypeDataLoader
    {
        if ($this->commentObjectTypeDataLoader === null) {
            /** @var CommentObjectTypeDataLoader */
            $commentObjectTypeDataLoader = $this->instanceManager->getInstance(CommentObjectTypeDataLoader::class);
            $this->commentObjectTypeDataLoader = $commentObjectTypeDataLoader;
        }
        return $this->commentObjectTypeDataLoader;
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
