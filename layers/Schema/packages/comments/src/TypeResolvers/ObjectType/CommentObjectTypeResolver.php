<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Comments\RelationalTypeDataLoaders\ObjectType\CommentTypeDataLoader;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class CommentObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected ?CommentTypeAPIInterface $commentTypeAPI = null;
    protected ?CommentTypeDataLoader $commentTypeDataLoader = null;

    #[Required]
    final public function autowireCommentObjectTypeResolver(
        CommentTypeAPIInterface $commentTypeAPI,
        CommentTypeDataLoader $commentTypeDataLoader,
    ): void {
        $this->commentTypeAPI = $commentTypeAPI;
        $this->commentTypeDataLoader = $commentTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'Comment';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Comments added to custom posts', 'comments');
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
