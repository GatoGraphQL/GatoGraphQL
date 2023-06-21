<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

abstract class AbstractBundleExtension extends AbstractExtension implements BundleExtensionInterface
{
    /**
     * Provide the Extensions that are bundled by the Extension Bundle
     *
     * @return array<class-string<ExtensionInterface>>
     */
    public function getBundledExtensionClasses(): array
    {
        return array_values($this->getBundledExtensionSlugModuleClasses());
    }

    /**
     * Convenience method, to return an array with the extension
     * slug => extension Module class
     *
     * @return array<string,class-string<ModuleInterface>>
     */
    abstract public function getBundledExtensionSlugModuleClasses(): array;

    /**
     * Provide the Extension plugin filenames that are bundled
     * by this Extension Bundle
     *
     * @return string[]
     */
    public function getBundledExtensionFilenames(): array
    {
        $extensions = array_keys($this->getBundledExtensionSlugModuleClasses());
        return static::getExtensionFilenames($extensions);
    }

    /**
     * @param string[] $extensions
     * @return string[]
     */
    protected static function getExtensionFilenames(array $extensions): array
    {
        return array_map(
            fn (string $extension) => $extension . '/' . $extension . '.php',
            $extensions
        );
    }
}
