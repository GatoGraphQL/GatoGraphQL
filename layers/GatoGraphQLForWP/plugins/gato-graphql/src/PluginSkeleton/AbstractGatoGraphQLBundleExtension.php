<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

abstract class AbstractGatoGraphQLBundleExtension extends AbstractBundleExtension
{
    /**
     * @param string[] $extensionSlugs
     * @return string[]
     */
    protected function getExtensionFilenames(array $extensionSlugs): array
    {
        return array_map(
            fn (string $extensionSlug) => $extensionSlug . '/gato-graphql-' . $extensionSlug . '.php',
            $extensionSlugs
        );
    }
}
