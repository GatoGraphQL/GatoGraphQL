<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Constants;

class PaginationParams
{
    // Paged param: It is 'pagenum' and not 'paged', because if so WP does a redirect to re-adjust the URL
    // From https://www.mesym.com/action?paged=2 it redirects to https://www.mesym.com/action/paged/2/
    public const PAGE_NUMBER = 'pagenum';
    public const LIMIT = 'limit';
}
