<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Constants;

use PoPCMSSchema\CustomPosts\Constants\CustomPostOrderBy as UpstreamCustomPostOrderBy;

class CustomPostOrderBy extends UpstreamCustomPostOrderBy
{
    public final const NONE = 'NONE';
    public final const COMMENT_COUNT = 'COMMENT_COUNT';
    public final const RANDOM = 'RANDOM';
    public final const MODIFIED_DATE = 'MODIFIED_DATE';
    public final const RELEVANCE = 'RELEVANCE';
    public final const TYPE = 'TYPE';
    public final const PARENT = 'PARENT';
    public final const MENU_ORDER = 'MENU_ORDER';
    // public final const POST__IN = 'POST__IN';
    // public final const POST_PARENT__IN = 'POST_PARENT__IN';
}
