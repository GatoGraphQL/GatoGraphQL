<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\Hooks;

use PoPCMSSchema\CustomPosts\Environment;
use PoPCMSSchema\CustomPosts\Module;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\Root\Module\ModuleConfigurationHelpers;

/**
 * Add a default CPT for Custom Posts
 */
abstract class AbstractAddDefaultCustomPostTypeModuleConfigurationHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $hookName = ModuleConfigurationHelpers::getHookName(
            Module::class,
            Environment::QUERYABLE_CUSTOMPOST_TYPES
        );
        App::addFilter(
            $hookName,
            fn (array $queryableCustomPostTypes) => [...$queryableCustomPostTypes, $this->getCustomPostType()]
        );
    }

    abstract protected function getCustomPostType(): string;
}
