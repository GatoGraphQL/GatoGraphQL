<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;

class BundleExtensionModuleResolver extends AbstractBundleExtensionModuleResolver
{
    public const ALL_EXTENSIONS = Plugin::NAMESPACE . '\\bundle-extensions\\all-extensions';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::ALL_EXTENSIONS,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::ALL_EXTENSIONS => \__('All Extensions', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::ALL_EXTENSIONS => \__('When being a member of the Gato GraphQL Club, you have access to all the extensions (from now and the future), via a single bundle.', 'gato-graphql'),
            default => parent::getDescription($module),
        };
    }

    public function getPriority(): int
    {
        return 20;
    }
}
