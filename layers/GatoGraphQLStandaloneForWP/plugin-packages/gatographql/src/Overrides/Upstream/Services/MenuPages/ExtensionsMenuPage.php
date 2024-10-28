<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Services\MenuPages\ExtensionsMenuPage as UpstreamExtensionsMenuPage;

class ExtensionsMenuPage extends UpstreamExtensionsMenuPage
{
    public function isServiceEnabled(): bool
    {
        return false;
    }
}
