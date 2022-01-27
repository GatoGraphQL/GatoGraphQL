<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\Constants;

use PoPCMSSchema\Comments\Constants\CommentOrderBy as UpstreamCommentOrderBy;

class CommentOrderBy extends UpstreamCommentOrderBy
{
    public const AUTHOR_EMAIL = 'AUTHOR_EMAIL';
    public const AUTHOR_IP = 'AUTHOR_IP';
    public const AUTHOR_URL = 'AUTHOR_URL';
    public const KARMA = 'KARMA';
    public const NONE = 'NONE';
}
