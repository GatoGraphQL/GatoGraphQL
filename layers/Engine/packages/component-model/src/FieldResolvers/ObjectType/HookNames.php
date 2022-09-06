<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

class HookNames
{
    public final const OBJECT_TYPE_FIELD_DESCRIPTION = __CLASS__ . ':object-type-field-description';
    public final const OBJECT_TYPE_FIELD_DEPRECATION_MESSAGE = __CLASS__ . ':object-type-field-deprecation-message';
    public final const OBJECT_TYPE_FIELD_EXTENSIONS = __CLASS__ . ':object-type-field-extensions';

    public final const OBJECT_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS = __CLASS__ . ':object-type-field-arg-name-type-resolvers';
    public final const OBJECT_TYPE_SENSITIVE_FIELD_ARG_NAMES = __CLASS__ . ':object-type-sensitive-field-arg-names';
    public final const OBJECT_TYPE_FIELD_ARG_DESCRIPTION = __CLASS__ . ':object-type-field-arg-description';
    public final const OBJECT_TYPE_FIELD_ARG_DEFAULT_VALUE = __CLASS__ . ':object-type-field-arg-default-value';
    public final const OBJECT_TYPE_FIELD_ARG_TYPE_MODIFIERS = __CLASS__ . ':object-type-field-arg-type-modifiers';
    public final const OBJECT_TYPE_FIELD_ARG_EXTENSIONS = __CLASS__ . ':object-type-field-arg-extensions';
}
