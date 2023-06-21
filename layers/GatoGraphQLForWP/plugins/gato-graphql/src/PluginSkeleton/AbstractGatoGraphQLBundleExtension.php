<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use PoP\Root\Module\ModuleInterface;

abstract class AbstractGatoGraphQLBundleExtension extends AbstractBundleExtension
{
    /**
     * Provide the Extensions that are bundled by the Extension Bundle
     *
     * @return array<class-string<ExtensionInterface>>
     */
    final public function getBundledExtensionClasses(): array
    {
        return array_values($this->getGatoGraphQLBundledExtensionSlugModuleClasses());
    }

    /**
     * Convenience method, to return an array with the extension
     * slug => extension Module class
     *
     * @return array<string,class-string<ModuleInterface>>
     */
    abstract public function getGatoGraphQLBundledExtensionSlugModuleClasses(): array;

    /**
     * Provide the Extension plugin filenames that are bundled
     * by this Extension Bundle
     *
     * @return string[]
     */
    final public function getBundledExtensionFilenames(): array
    {
        $extensions = array_keys($this->getGatoGraphQLBundledExtensionSlugModuleClasses());
        return static::getGatoGraphQLExtensionFilenames($extensions);
    }
}
