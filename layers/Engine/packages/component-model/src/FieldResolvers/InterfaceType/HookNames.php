<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

class HookNames
{
    public final const INTERFACE_TYPE_FIELD_DESCRIPTION = __CLASS__ . ':interface-type-field-description';
    public final const INTERFACE_TYPE_FIELD_DEPRECATION_MESSAGE = __CLASS__ . ':interface-type-field-deprecation-message';
    public final const INTERFACE_TYPE_FIELD_EXTENSIONS = __CLASS__ . ':interface-type-field-extensions';

    public final const INTERFACE_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS = __CLASS__ . ':interface-type-field-arg-name-type-resolvers';
    public final const INTERFACE_TYPE_ADMIN_FIELD_ARG_NAMES = __CLASS__ . ':interface-type-admin-field-arg-names';
    public final const INTERFACE_TYPE_FIELD_ARG_DESCRIPTION = __CLASS__ . ':interface-type-field-arg-description';
    public final const INTERFACE_TYPE_FIELD_ARG_DEFAULT_VALUE = __CLASS__ . ':interface-type-field-arg-default-value';
    public final const INTERFACE_TYPE_FIELD_ARG_TYPE_MODIFIERS = __CLASS__ . ':interface-type-field-arg-type-modifiers';
    public final const INTERFACE_TYPE_FIELD_ARG_EXTENSIONS = __CLASS__ . ':interface-type-field-arg-extensions';
}
