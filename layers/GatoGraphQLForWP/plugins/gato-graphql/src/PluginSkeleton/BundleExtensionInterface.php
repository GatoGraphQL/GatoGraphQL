<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\PluginSkeleton\ExtensionInterface;

interface BundleExtensionInterface extends ExtensionInterface
{
    /**
     * Provide the Extension classes that are bundled
     * by this Extension Bundle
     *
     * @return array<class-string<ExtensionInterface>>
     */
    public function getBundledExtensionClasses(): array;
    
    /**
     * Provide the Extension plugin filenames that are bundled
     * by this Extension Bundle
     *
     * @return string[]
     */
    public static function getBundledExtensionFilenames(): array;
}
