<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\ComponentConfiguration;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\RelationalTypeResolverDecorators\AbstractRelationalTypeResolverDecorator;

abstract class AbstractPrivateSchemaRelationalTypeResolverDecorator extends AbstractRelationalTypeResolverDecorator
{
    /**
     * Enable only for private schema
     */
    public function enabled(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return
            ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode() ||
            ComponentConfiguration::usePrivateSchemaMode();
    }

    protected function getSchemaMode(): string
    {
        return SchemaModes::PRIVATE_SCHEMA_MODE;
    }
}
