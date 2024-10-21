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

    protected function getInitializeExtensionPriority(): int
    {
        return parent::getInitializeExtensionPriority() + 10;
    }
}
