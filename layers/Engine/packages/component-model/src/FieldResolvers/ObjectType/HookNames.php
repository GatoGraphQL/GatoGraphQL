<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

class HookNames
{
    public const OBJECT_TYPE_FIELD_DESCRIPTION = __CLASS__ . ':object-type-field-description';
    public const OBJECT_TYPE_FIELD_DEPRECATION_MESSAGE = __CLASS__ . ':object-type-field-deprecation-message';

    public const OBJECT_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS = __CLASS__ . ':object-type-field-arg-name-type-resolvers';
    public const OBJECT_TYPE_ADMIN_FIELD_ARG_NAMES = __CLASS__ . ':object-type-admin-field-arg-names';
    public const OBJECT_TYPE_FIELD_ARG_DESCRIPTION = __CLASS__ . ':object-type-field-arg-description';
    public const OBJECT_TYPE_FIELD_ARG_DEFAULT_VALUE = __CLASS__ . ':object-type-field-arg-default-value';
    public const OBJECT_TYPE_FIELD_ARG_TYPE_MODIFIERS = __CLASS__ . ':object-type-field-arg-type-modifiers';
}
