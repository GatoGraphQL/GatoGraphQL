<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\Constants;

use PoPSchema\Posts\Constants\PostOrderBy as UpstreamPostOrderBy;

class PostOrderBy extends UpstreamPostOrderBy
{
    public const COMMENT_COUNT = 'COMMENT_COUNT';
    public const RANDOM = 'RANDOM';
    public const MODIFIED_DATE = 'MODIFIED_DATE';
    public const RELEVANCE = 'RELEVANCE';
}
