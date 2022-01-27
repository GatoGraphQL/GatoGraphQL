<?php

declare(strict_types=1);

namespace PoPWPSchema\Users\Constants;

use PoPCMSSchema\Users\Constants\UserOrderBy as UpstreamUserOrderBy;

class UserOrderBy extends UpstreamUserOrderBy
{
    public const INCLUDE = 'INCLUDE';
    public const WEBSITE_URL = 'WEBSITE_URL';
    public const NICENAME = 'NICENAME';
    public const EMAIL = 'EMAIL';
}
