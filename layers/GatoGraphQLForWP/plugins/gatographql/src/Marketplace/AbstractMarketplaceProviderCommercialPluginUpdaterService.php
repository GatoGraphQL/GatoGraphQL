<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use PoP\Root\Services\BasicServiceTrait;

/**
 * Copied code from `Make-Lemonade/lemonsqueezy-wp-updater-example`
 *
 * @see https://github.com/Make-Lemonade/lemonsqueezy-wp-updater-example
 * @see https://github.com/Make-Lemonade/lemonsqueezy-wp-updater-example/blob/7c0c71876309939b07d96e270f4db8568f3148cb/includes/class-plugin-updater.php
 */
abstract class AbstractMarketplaceProviderCommercialPluginUpdaterService implements MarketplaceProviderCommercialPluginUpdaterServiceInterface
{
    use BasicServiceTrait;

    /**
     * @var array<string,array{id:string,version:string}> Key: plugin slug, Value: array of entries: id, version
     */
    protected array $pluginSlugDataEntries;

	protected string $apiURL;

	/**
     * @var array<string,array{id:string,version:string}> Key: plugin slug, Value: Cache key
     */
    protected array $pluginSlugCacheKeys;

	/**
	 * Only disable this for debugging
	 */
	protected bool $cacheAllowed = true;
}
