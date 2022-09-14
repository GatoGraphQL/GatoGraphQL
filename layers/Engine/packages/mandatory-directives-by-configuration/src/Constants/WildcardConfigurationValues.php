<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\Constants;

/**
 * When storing the ACL configuration for fields,
 * we can use value "*" to represent "any type or interface"
 * and also "any field (for a certain type or interface)"
 */
class WildcardConfigurationValues
{
    final public const MATCH_ANY = '*';
}
