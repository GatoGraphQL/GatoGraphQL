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
        return array_map(
            fn (array $item): string => $item[1],
            $this->getBundledExtensionDataItems()
        );
    }

    /**
     * Convenience method to provide an array with the required data:
     *
     * [0]: Extension slug
     * [1]: Extension class
     *
     * @return array<array{0:string,1:class-string<ExtensionInterface>}>
     */
    abstract public function getBundledExtensionDataItems(): array;

    /**
     * Provide the Extension plugin filenames that are bundled
     * by this Extension Bundle
     *
     * @return string[]
     */
    public function getBundledExtensionSlugs(): array
    {
        return array_map(
            fn (array $item): string => $item[0],
            $this->getBundledExtensionDataItems()
        );
    }

    /**
     * When the Bundle is active, "pretend" that its bundled
     * extensions are also active, so that it paints them
     * without background color in the Extensions page.
     *
     * Do this by adding a hook to `get_option` for the
     * "active_plugins" option.
     *
     * @see wordpress/wp-includes/option.php method `get_option`
     */
    protected function doSetup(): void
    {
        parent::doSetup();

        $option = 'active_plugins';
        \add_filter(
            "option_{$option}",
            /**
             * @param string[] $activePlugins
             * @return string[]
             */
            function (array $activePlugins): array {
                return array_merge(
                    $activePlugins,
                    $this->getExtensionPluginFilenames($this->getBundledExtensionSlugs()),
                );
            }
        );
    }

    /**
     * @param string[] $extensionSlugs
     * @return string[]
     */
    protected function getExtensionPluginFilenames(array $extensionSlugs): array
    {
        return array_map(
            fn (string $extensionSlug) => 'gato-graphql-' . $extensionSlug . '/gato-graphql-' . $extensionSlug . '.php',
            $extensionSlugs
        );
    }
}
