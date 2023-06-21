<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\PluginSkeleton\ExtensionInterface;

interface BundleExtensionInterface extends ExtensionInterface
{
    /**
     * Provide the Extensions that are bundled by the Extension Bundle
     *
     * @return array<class-string<ExtensionInterface>>
     */
    public static function getBundledExtensionClasses(): array;
}
