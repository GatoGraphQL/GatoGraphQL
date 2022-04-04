<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\EnumType;

class HookNames
{
    public final const ENUM_VALUES = __CLASS__ . ':enum-values';
    public final const ADMIN_ENUM_VALUES = __CLASS__ . ':admin-enum-values';
    public final const ENUM_VALUE_DESCRIPTION = __CLASS__ . ':enum-value-description';
    public final const ENUM_VALUE_DEPRECATION_MESSAGE = __CLASS__ . ':enum-value-deprecation-message';
    public final const ENUM_VALUE_EXTENSIONS = __CLASS__ . ':enum-value-extensions';
}
