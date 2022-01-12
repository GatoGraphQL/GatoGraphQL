<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use PoP\Root\App;

abstract class AbstractLoadingCPTSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    /**
     * Initialize the configuration if visiting the corresponding CPT
     */
    protected function getCustomPostID(): ?int
    {
        if (\is_singular($this->getCustomPostType())) {
            return App::getState(['routing', 'queried-object-id']);
        }
        return null;
    }

    abstract protected function getCustomPostType(): string;
}
