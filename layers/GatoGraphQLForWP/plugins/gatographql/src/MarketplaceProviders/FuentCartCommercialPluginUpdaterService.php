<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialPluginUpdatedPluginData;
use GatoGraphQL\GatoGraphQL\MarketplaceProviders\MarketplaceProviderCommercialPluginUpdaterServiceInterface;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use PoP\ComponentModel\App;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;

use function wp_remote_get;

/**
 * Based on code from FluentCart's `PluginUpdater` class
 *
 * @see wp-content/plugins/fluent-cart-pro/app/Services/PluginManager/PluginUpdater.php
 */
class FuentCartCommercialPluginUpdaterService extends AbstractBasicService implements MarketplaceProviderCommercialPluginUpdaterServiceInterface
{
    use FuentCartMarketplaceProviderServiceTrait;


    /**
     * All code below copied from FluentCart's `PluginUpdater` class
     *
     * @see wp-content/plugins/fluent-cart-pro/app/Services/PluginManager/PluginUpdater.php
     *
     * ------------------------------------------------------------
     */


}
