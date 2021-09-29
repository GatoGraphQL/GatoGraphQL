<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\GlobalObjectTypeFieldResolverTrait;

abstract class AbstractGlobalUserStateObjectTypeFieldResolver extends AbstractUserStateObjectTypeFieldResolver
{
    use GlobalObjectTypeFieldResolverTrait;
}
