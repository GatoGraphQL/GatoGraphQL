<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\Constants;

use PoPCMSSchema\Comments\Constants\CommentOrderBy as UpstreamCommentOrderBy;

class CommentOrderBy extends UpstreamCommentOrderBy
{
    public final const AUTHOR_EMAIL = 'AUTHOR_EMAIL';
    public final const AUTHOR_IP = 'AUTHOR_IP';
    public final const AUTHOR_URL = 'AUTHOR_URL';
    public final const KARMA = 'KARMA';
    public final const NONE = 'NONE';
}
