<?php

declare(strict_types=1);

namespace PoP\Definitions\Configuration;

use PoP\Definitions\Constants\Params;
use PoP\Definitions\Constants\ParamValues;

class Request
{
    public static function isMangled(): bool
    {
        // By default, it is mangled, if not mangled then param "mangled" must have value "none"
        $mangled = \PoP\Root\App::request(Params::MANGLED) ?? \PoP\Root\App::query(Params::MANGLED);
        return $mangled !== ParamValues::MANGLED_NONE;
    }

    public static function getMangledValue(): string
    {
        return self::isMangled() ? '' : ParamValues::MANGLED_NONE;
    }
}
