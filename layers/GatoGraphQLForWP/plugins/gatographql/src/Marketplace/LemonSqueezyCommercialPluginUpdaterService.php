<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialPluginUpdatedPluginData;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use PoP\ComponentModel\App;
use PoP\Root\Exception\ShouldNotHappenException;

use WP_Error;
use function wp_remote_get;

/**
 * Copied code from `Make-Lemonade/lemonsqueezy-wp-updater-example`
 *
 * @see https://github.com/Make-Lemonade/lemonsqueezy-wp-updater-example
 * @see https://github.com/Make-Lemonade/lemonsqueezy-wp-updater-example/blob/7c0c71876309939b07d96e270f4db8568f3148cb/includes/class-plugin-updater.php
 */
class LemonSqueezyCommercialPluginUpdaterService extends AbstractMarketplaceProviderCommercialPluginUpdaterService
{
	protected string $apiURL;
    
    /**
     * Use the Marketplace provider's service to
     * update the active commercial extensions
     *
     * @param array<string,string> $licenseKeys Key: Extension Slug, Value: License Key
     *
     * @throws ShouldNotHappenException If initializing the service more than once
     */
    public function setupMarketplacePluginUpdaterForExtensions(
        array $licenseKeys,
    ): void {
        parent::setupMarketplacePluginUpdaterForExtensions($licenseKeys);

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $this->apiURL = $this->providePluginUpdatesAPIURL($moduleConfiguration->getMarketplaceProviderPluginUpdatesServerURL());
    }

    protected function providePluginUpdatesAPIURL(string $pluginUpdatesServerURL): string
    {
        return $pluginUpdatesServerURL . '/wp-json/lsq/v1';
    }

	/**
	 * Fetch the update info from the remote server running the Lemon Squeezy plugin.
	 */
	protected function getRemotePluginData(CommercialPluginUpdatedPluginData $pluginData): array|WP_Error
    {
		$url = $this->apiURL . "/update?license_key={$pluginData->licenseKey}";
        return wp_remote_get($url, ['timeout' => 10]);
	}
}
