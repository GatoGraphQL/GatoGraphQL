<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ConditionalOnComponent\Users\TypeAPIs;

interface CommentTypeAPIInterface
{
    public function getCommentUserId(object $comment): string | int | null;
}
