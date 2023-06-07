<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Services\Scripts;

use GatoGraphQL\GatoGraphQL\PluginSkeleton\ExtensionInterface;
use GatoGraphQL\GatoGraphQL\Services\Scripts\ExtensionScriptTrait as UpstreamExtensionScriptTrait;
use GatoGraphQL\TestingSchema\GatoGraphQLExtension;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (Gato GraphQL)
 */
trait ExtensionScriptTrait
{
    use UpstreamExtensionScriptTrait;

    /**
     * @return class-string<ExtensionInterface>
     */
    protected function getExtensionClass(): string
    {
        return GatoGraphQLExtension::class;
    }
}
