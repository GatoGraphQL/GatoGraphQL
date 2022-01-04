<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\Root\Managers\ComponentManager;
use PoP\AccessControl\Component;
use PoP\AccessControl\ComponentConfiguration;
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
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return
            $componentConfiguration->enableIndividualControlForPublicPrivateSchemaMode() ||
            !$componentConfiguration->usePrivateSchemaMode();
    }

    protected function getSchemaMode(): string
    {
        return SchemaModes::PUBLIC_SCHEMA_MODE;
    }
}
