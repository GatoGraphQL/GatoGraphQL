<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\FieldResolvers\ObjectType;

use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;

trait MaybeCommentableCustomPostObjectTypeFieldResolverTrait
{
    abstract protected function getCommentTypeAPI(): CommentTypeAPIInterface;

    public function isServiceEnabled(): bool
    {
        return $this->areCommentsEnabledForCustomPostType();
    }

    protected function areCommentsEnabledForCustomPostType(): bool
    {
        return $this->getCommentTypeAPI()->doesCustomPostTypeSupportComments($this->getCustomPostType());
    }

    abstract protected function getCustomPostType(): string;
}
