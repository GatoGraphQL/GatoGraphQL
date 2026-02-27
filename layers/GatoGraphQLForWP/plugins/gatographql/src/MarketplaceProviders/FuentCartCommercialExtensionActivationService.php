<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseStatus;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\HTTPRequestNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseObjectProperties;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;
use GatoGraphQL\GatoGraphQL\StaticHelpers\PluginVersionHelpers;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;

use function wp_remote_post;

class FuentCartCommercialExtensionActivationService extends AbstractBasicService implements MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    use FuentCartMarketplaceProviderServiceTrait;
}
