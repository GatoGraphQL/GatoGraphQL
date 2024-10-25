<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

/**
 * Base class to set the configuration in all the PoP components for the Extension.
 */
abstract class AbstractExtensionInitializationConfiguration extends AbstractPluginInitializationConfiguration implements ExtensionInitializationConfigurationInterface
{
    public function __construct(
        protected ExtensionInterface $extension
    ) {        
    }
}
