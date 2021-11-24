<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\Constants;

use PoPSchema\Comments\Constants\CommentOrderBy as UpstreamCommentOrderBy;

class CommentOrderBy extends UpstreamCommentOrderBy
{
    public const KARMA = 'KARMA';
    public const NONE = 'NONE';
}
