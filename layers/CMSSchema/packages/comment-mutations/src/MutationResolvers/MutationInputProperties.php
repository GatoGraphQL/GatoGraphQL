<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

class MutationInputProperties
{
    public final const CUSTOMPOST_ID = 'customPostID';
    public final const COMMENT = 'comment';
    public final const PARENT_COMMENT_ID = 'parentCommentID';
    public final const AUTHOR_NAME = 'authorName';
    public final const AUTHOR_EMAIL = 'authorEmail';
    public final const AUTHOR_URL = 'authorURL';
}
