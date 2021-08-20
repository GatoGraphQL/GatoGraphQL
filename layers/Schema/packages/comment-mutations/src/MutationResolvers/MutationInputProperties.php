<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\MutationResolvers;

class MutationInputProperties
{
    public const CUSTOMPOST_ID = 'customPostID';
    public const COMMENT = 'comment';
    public const PARENT_COMMENT_ID = 'parentCommentID';
    public const AUTHOR_NAME = 'authorName';
    public const AUTHOR_EMAIL = 'authorEmail';
    public const AUTHOR_URL = 'authorURL';
}
