<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

abstract class AbstractBundleExtension extends AbstractExtension implements BundleExtensionInterface
{
    /**
     * @param string[] $extensions
     * @return string[]
     */
    protected static function getGatoGraphQLExtensionFilenames(array $extensions): array
    {
        return array_map(
            fn (string $extension) => $extension . '/gato-graphql-' . $extension . '.php',
            $extensions
        );
    }
}
