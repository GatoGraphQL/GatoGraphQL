<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\Constants;

/**
 * When storing the ACL configuration for fields,
 * we can use value "*" to represent "any type or interface"
 * and also "any field (within a certain type)"
 */
class WildcardConfigurationValues
{
    final public const TYPE_OR_INTERFACE_RESOLVER_CLASS = '*';
    final public const FIELD_NAME = '*';
}
