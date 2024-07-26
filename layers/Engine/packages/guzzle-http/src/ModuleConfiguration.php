<?php

declare(strict_types=1);

namespace PoP\GuzzleHTTP;

use PoP\Root\Module\AbstractModuleConfiguration;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function getGuzzleRequestReferer(): ?string
    {
        $envVariable = Environment::GUZZLE_REQUEST_REFERER;
        $defaultValue = null;

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
