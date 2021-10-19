<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

class HookNames
{
    public const OBJECT_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS = __CLASS__ . ':object-type-field-arg-name-type-resolvers';
    public const OBJECT_TYPE_FIELD_ARG_DESCRIPTION = __CLASS__ . ':object-type-field-arg-description';
    public const OBJECT_TYPE_FIELD_ARG_DEFAULT_VALUE = __CLASS__ . ':object-type-field-arg-default-value';
    public const OBJECT_TYPE_FIELD_ARG_TYPE_MODIFIERS = __CLASS__ . ':object-type-field-arg-type-modifiers';

    public const INTERFACE_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS = __CLASS__ . ':interface-type-field-arg-name-type-resolvers';
    public const INTERFACE_TYPE_FIELD_ARG_DESCRIPTION = __CLASS__ . ':interface-type-field-arg-description';
    public const INTERFACE_TYPE_FIELD_ARG_DEFAULT_VALUE = __CLASS__ . ':interface-type-field-arg-default-value';
    public const INTERFACE_TYPE_FIELD_ARG_TYPE_MODIFIERS = __CLASS__ . ':interface-type-field-arg-type-modifiers';
}
