<?php

declare(strict_types=1);

namespace PoPWPSchema\Users\Constants;

use PoPCMSSchema\Users\Constants\UserOrderBy as UpstreamUserOrderBy;

class UserOrderBy extends UpstreamUserOrderBy
{
    public final const INCLUDE = 'INCLUDE';
    public final const WEBSITE_URL = 'WEBSITE_URL';
    public final const NICENAME = 'NICENAME';
    public final const EMAIL = 'EMAIL';
}
