<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

abstract class AbstractGatoGraphQLBundleExtension extends AbstractBundleExtension
{
    /**
     * @param string[] $extensions
     * @return string[]
     */
    protected static function getExtensionFilenames(array $extensions): array
    {
        return array_map(
            fn (string $extension) => $extension . '/gato-graphql-' . $extension . '.php',
            $extensions
        );
    }
}
