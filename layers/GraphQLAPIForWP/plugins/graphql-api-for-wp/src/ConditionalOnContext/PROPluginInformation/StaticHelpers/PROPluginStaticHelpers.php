<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\StaticHelpers;

use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use PoP\ComponentModel\App;

class PROPluginStaticHelpers
{
    public static function getPROTitle(string $title): string
    {
        return sprintf(
            \__('🔒 %s', 'graphql-api'),
            $title
        );
    }

    public static function getGoPROToUnlockAnchorHTML(string $class = ''): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return \sprintf(
            '<a href="%s" target="%s" class="%s">%s</a>',
            $moduleConfiguration->getPROPluginWebsiteURL(),
            '_blank',
            $class,
            \__('Go PRO to unlock! 🔓', 'graphql-api')
        );
    }
}
