<?php

declare(strict_types=1);

namespace PoP\Definitions\Configuration;

class Request
{
    public const URLPARAM_MANGLED = 'mangled';
    public const URLPARAMVALUE_MANGLED_NONE = 'none';

    public static function isMangled(): bool
    {
        // By default, it is mangled, if not mangled then param "mangled" must have value "none"
        // Coment Leo 13/01/2017: getVars() can't function properly since it references objects which have not been initialized yet,
        // when called at the very beginning. So then access the request directly
        return !isset($_REQUEST[self::URLPARAM_MANGLED]) || !$_REQUEST[self::URLPARAM_MANGLED] || $_REQUEST[self::URLPARAM_MANGLED] != self::URLPARAMVALUE_MANGLED_NONE;
    }
}
