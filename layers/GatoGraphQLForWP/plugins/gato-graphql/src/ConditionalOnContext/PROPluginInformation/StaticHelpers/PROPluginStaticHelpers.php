<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\StaticHelpers;

use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use PoP\ComponentModel\App;

class PROPluginStaticHelpers
{
    public static function getPROTitle(
        string $title,
        ?string $recipeEntryPROExtensionModule = null
    ): string {
        return sprintf(
            \__('%s %s', 'gato-graphql'),
            $recipeEntryPROExtensionModule !== null ? '🔒' : '🔐',
            $title
        );
    }

    public static function getGoPROToUnlockAnchorHTML(string $class = ''): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return static::getUnlockFeaturesAnchorHTML(
            $moduleConfiguration->getPROPluginWebsiteURL(),
            \__('Go PRO to unlock!', 'gato-graphql'),
            $class,
        );
    }

    public static function getUnlockFeaturesAnchorHTML(
        string $url,
        string $title,
        string $class = '',
    ): string {
        return \sprintf(
            '<a href="%s" target="%s" class="%s">%s</a>',
            $url,
            '_blank',
            $class,
            sprintf(
                \__('%s 🔓', 'gato-graphql'),
                $title,
            )
        );
    }
}
