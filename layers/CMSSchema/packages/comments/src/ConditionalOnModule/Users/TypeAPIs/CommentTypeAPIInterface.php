<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\TypeAPIs;

interface CommentTypeAPIInterface
{
    public function getCommentUserId(object $comment): string | int | null;
}
