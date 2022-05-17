<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\Root\App;
use PoP\AccessControl\Module;
use PoP\AccessControl\ModuleConfiguration;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\ComponentModel\RelationalTypeResolverDecorators\AbstractRelationalTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

abstract class AbstractPublicSchemaRelationalTypeResolverDecorator extends AbstractRelationalTypeResolverDecorator
{
    /**
     * Enable only for public schema
     */
    public function enabled(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return
            $moduleConfiguration->enableIndividualControlForPublicPrivateSchemaMode() ||
            !$moduleConfiguration->usePrivateSchemaMode();
    }

    protected function getSchemaMode(): string
    {
        return SchemaModes::PUBLIC_SCHEMA_MODE;
    }
}
