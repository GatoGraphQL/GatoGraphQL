<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\SelfFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;

abstract class AbstractSchemaFieldResolver extends AbstractFieldResolver implements FieldSchemaDefinitionResolverInterface
{
    use SelfFieldSchemaDefinitionResolverTrait;
}
