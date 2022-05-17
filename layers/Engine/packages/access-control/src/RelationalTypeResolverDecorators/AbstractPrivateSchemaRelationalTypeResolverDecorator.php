<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\Root\App;
use PoP\AccessControl\Module;
use PoP\AccessControl\ModuleConfiguration;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\ComponentModel\RelationalTypeResolverDecorators\AbstractRelationalTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

abstract class AbstractPrivateSchemaRelationalTypeResolverDecorator extends AbstractRelationalTypeResolverDecorator
{
    /**
     * Enable only for private schema
     */
    public function enabled(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return
            $moduleConfiguration->enableIndividualControlForPublicPrivateSchemaMode() ||
            $moduleConfiguration->usePrivateSchemaMode();
    }

    protected function getSchemaMode(): string
    {
        return SchemaModes::PRIVATE_SCHEMA_MODE;
    }
}
