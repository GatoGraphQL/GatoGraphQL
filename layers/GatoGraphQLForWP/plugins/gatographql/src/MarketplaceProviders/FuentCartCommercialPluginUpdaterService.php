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

class FuentCartCommercialPluginUpdaterService extends AbstractBasicService implements MarketplaceProviderCommercialPluginUpdaterServiceInterface
{
    use FuentCartMarketplaceProviderServiceTrait;
}
