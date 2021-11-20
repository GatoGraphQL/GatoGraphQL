<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

class HookNames
{
    public const INTERFACE_TYPE_FIELD_DESCRIPTION = __CLASS__ . ':interface-type-field-description';
    public const INTERFACE_TYPE_FIELD_DEPRECATION_MESSAGE = __CLASS__ . ':interface-type-field-deprecation-message';

    public const INTERFACE_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS = __CLASS__ . ':interface-type-field-arg-name-type-resolvers';
    public const INTERFACE_TYPE_FIELD_ARG_DESCRIPTION = __CLASS__ . ':interface-type-field-arg-description';
    public const INTERFACE_TYPE_FIELD_ARG_DEFAULT_VALUE = __CLASS__ . ':interface-type-field-arg-default-value';
    public const INTERFACE_TYPE_FIELD_ARG_TYPE_MODIFIERS = __CLASS__ . ':interface-type-field-arg-type-modifiers';
}
