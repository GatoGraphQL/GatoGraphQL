<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Constants;

use PoPSchema\CustomPosts\Constants\CustomPostOrderBy as UpstreamCustomPostOrderBy;

class CustomPostOrderBy extends UpstreamCustomPostOrderBy
{
    public const COMMENT_COUNT = 'COMMENT_COUNT';
    public const RANDOM = 'RANDOM';
    public const MODIFIED_DATE = 'MODIFIED_DATE';
    public const RELEVANCE = 'RELEVANCE';
}
