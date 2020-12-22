<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\AccessControl\ComponentConfiguration;
use PoP\AccessControl\Hooks\AbstractAccessControlForDirectivesHookSet;
use PoP\AccessControl\Schema\SchemaModes;

abstract class AbstractAccessControlForDirectivesInPrivateSchemaHookSet extends AbstractAccessControlForDirectivesHookSet
{
    protected function enabled(): bool
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
