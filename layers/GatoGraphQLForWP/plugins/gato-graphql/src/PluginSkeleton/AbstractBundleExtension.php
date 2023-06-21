<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\PluginSkeleton\ExtensionInterface;

abstract class AbstractBundleExtension extends AbstractExtension implements BundleExtensionInterface
{
    /**
     * Provide the Extensions that are bundled by the Extension Bundle
     *
     * @return array<class-string<ExtensionInterface>>
     */
    public function getBundledExtensionClasses(): array
    {
        return array_values($this->getBundledExtensionSlugClasses());
    }

    /**
     * Convenience method, to return an array with the extension
     * slug => extension Module class
     *
     * @return array<string,class-string<ExtensionInterface>>
     */
    abstract public function getBundledExtensionSlugClasses(): array;

    /**
     * Provide the Extension plugin filenames that are bundled
     * by this Extension Bundle
     *
     * @return string[]
     */
    public function getBundledExtensionFilenames(): array
    {
        $extensionSlugs = array_keys($this->getBundledExtensionSlugClasses());
        return $this->getExtensionFilenames($extensionSlugs);
    }

    /**
     * @param string[] $extensionSlugs
     * @return string[]
     */
    protected function getExtensionFilenames(array $extensionSlugs): array
    {
        return array_map(
            fn (string $extensionSlug) => $extensionSlug . '/' . $extensionSlug . '.php',
            $extensionSlugs
        );
    }
}
