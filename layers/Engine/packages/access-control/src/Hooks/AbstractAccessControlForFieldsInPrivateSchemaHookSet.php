<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\Engine\App;
use PoP\AccessControl\Component;
use PoP\AccessControl\ComponentConfiguration;
use PoP\AccessControl\Schema\SchemaModes;

abstract class AbstractAccessControlForFieldsInPrivateSchemaHookSet extends AbstractAccessControlForFieldsHookSet
{
    /**
     * Indicate if this hook is enabled
     */
    protected function enabled(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->canSchemaBePrivate();
    }

    protected function getSchemaMode(): string
    {
        return SchemaModes::PRIVATE_SCHEMA_MODE;
    }
}
