<?php

declare(strict_types=1);

namespace PoP\Engine\Configuration;

use PoP\Root\App;
use PoP\Engine\Constants\Params;

class Request
{
    public static function getHeadModule(): ?string
    {
        return App::query(Params::HEADCOMPONENT);
    }
}
