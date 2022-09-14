<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Constants;

/**
 * When storing the ACL configuration for fields,
 * we can use value "*" to represent "any type or interface"
 * and also "any field (for a certain type or interface)"
 */
class ConfigurationValues
{
    final public const ANY = '*';
}
