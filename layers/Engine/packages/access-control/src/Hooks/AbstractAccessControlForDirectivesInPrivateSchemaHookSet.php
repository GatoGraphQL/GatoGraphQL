<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\Root\Managers\ComponentManager;
use PoP\AccessControl\Component;
use PoP\AccessControl\ComponentConfiguration;
use PoP\AccessControl\Schema\SchemaModes;

abstract class AbstractAccessControlForDirectivesInPrivateSchemaHookSet extends AbstractAccessControlForDirectivesHookSet
{
    protected function enabled(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->canSchemaBePrivate();
    }

    protected function getSchemaMode(): string
    {
        return SchemaModes::PRIVATE_SCHEMA_MODE;
    }
}
