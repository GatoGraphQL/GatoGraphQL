<?php

declare(strict_types=1);

namespace PoP\Routing;

use PoP\Root\Routing\RequestNature as UpstreamRequestNature;

class RequestNature extends UpstreamRequestNature
{
    public final const HOME = 'home';
    public final const NOTFOUND = '404';
}
