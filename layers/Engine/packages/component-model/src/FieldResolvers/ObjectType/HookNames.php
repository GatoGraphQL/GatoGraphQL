<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

class HookNames
{
    public final const OBJECT_TYPE_FIELD_DESCRIPTION = __CLASS__ . ':object-type-field-description';
    public final const OBJECT_TYPE_FIELD_DEPRECATION_MESSAGE = __CLASS__ . ':object-type-field-deprecation-message';
    public final const OBJECT_TYPE_FIELD_EXTENSIONS = __CLASS__ . ':object-type-field-extensions';

    public final const OBJECT_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS = __CLASS__ . ':object-type-field-arg-name-type-resolvers';
    public final const OBJECT_TYPE_ADMIN_FIELD_ARG_NAMES = __CLASS__ . ':object-type-admin-field-arg-names';
    public final const OBJECT_TYPE_FIELD_ARG_DESCRIPTION = __CLASS__ . ':object-type-field-arg-description';
    public final const OBJECT_TYPE_FIELD_ARG_DEFAULT_VALUE = __CLASS__ . ':object-type-field-arg-default-value';
    public final const OBJECT_TYPE_FIELD_ARG_TYPE_MODIFIERS = __CLASS__ . ':object-type-field-arg-type-modifiers';
    public final const OBJECT_TYPE_FIELD_ARG_EXTENSIONS = __CLASS__ . ':object-type-field-arg-extensions';

    public final const OBJECT_TYPE_MUTATION_FIELD_ARGS = __CLASS__ . ':object-type-mutation-field-args';
    public final const OBJECT_TYPE_MUTATION_FIELD_ARGS_FOR_OBJECT = __CLASS__ . ':object-type-mutation-field-args-for-object';
}
