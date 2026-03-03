<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\CommercialPluginUpdaterServiceInterface;

/**
 * Provider interface for plugin updater services that can be
 * registered and selected by license key (e.g. LemonSqueezy, FluentCart).
 */
interface MarketplaceProviderCommercialPluginUpdaterServiceInterface extends CommercialPluginUpdaterServiceInterface
{
    public function getPriority(): int;

    public function canProcessLicense(string $licenseKey): bool;

    public function getMarketplaceVersion(): string;
}
