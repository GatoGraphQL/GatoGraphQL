<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Services\MenuPages\ModulesMenuPage as UpstreamModulesMenuPage;

class ModulesMenuPage extends UpstreamModulesMenuPage
{
    public function isServiceEnabled(): bool
    {
        return false;
    }
}
