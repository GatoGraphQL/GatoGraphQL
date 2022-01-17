<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Constants;

use PoPCMSSchema\CustomPosts\Constants\CustomPostOrderBy as UpstreamCustomPostOrderBy;

class CustomPostOrderBy extends UpstreamCustomPostOrderBy
{
    public const NONE = 'NONE';
    public const COMMENT_COUNT = 'COMMENT_COUNT';
    public const RANDOM = 'RANDOM';
    public const MODIFIED_DATE = 'MODIFIED_DATE';
    public const RELEVANCE = 'RELEVANCE';
    public const TYPE = 'TYPE';
    public const PARENT = 'PARENT';
    public const MENU_ORDER = 'MENU_ORDER';
    // public const POST__IN = 'POST__IN';
    // public const POST_PARENT__IN = 'POST_PARENT__IN';
}
