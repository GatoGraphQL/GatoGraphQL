<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

class HookNames
{
    public const FIELD_ARG_NAME_RESOLVERS = __CLASS__ . ':field-arg-name-resolvers';
    public const FIELD_ARG_DESCRIPTION = __CLASS__ . ':field-arg-description';
    public const FIELD_ARG_DEFAULT_VALUE = __CLASS__ . ':field-arg-default-value';
    public const FIELD_ARG_TYPE_MODIFIERS = __CLASS__ . ':field-arg-type-modifiers';
    public const FIELD_ARG_DEPRECATION_MESSAGE = __CLASS__ . ':field-arg-deprecation-message';
}
