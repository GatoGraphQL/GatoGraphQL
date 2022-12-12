<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMedia;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function enableFeaturedImageForGenericCustomPosts(): bool
    {
        $envVariable = Environment::ENABLE_FEATURED_IMAGE_FOR_GENERIC_CUSTOMPOSTS;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
