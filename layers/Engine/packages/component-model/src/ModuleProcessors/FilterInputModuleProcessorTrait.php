<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;

trait FilterInputModuleProcessorTrait
{
    use FieldOrDirectiveSchemaDefinitionResolverTrait;
    use SchemaFilterInputModuleProcessorTrait;
}
