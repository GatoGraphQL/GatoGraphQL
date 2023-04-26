<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\AppHelpers;
use PoP\Root\App;

abstract class AbstractLoadingCPTSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    /**
     * Only initialize once, for the main AppThread
     */
    public function isServiceEnabled(): bool
    {
        if (!AppHelpers::isMainAppThread()) {
            return false;
        }
        return parent::isServiceEnabled();
    }

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
