<?php

declare(strict_types=1);

namespace PoPSchema\TranslateDirective\Config;

use PoPSchema\TranslateDirective\Environment;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\Root\Container\ContainerBuilderUtils;
use PoPSchema\TranslateDirective\Translation\TranslationServiceInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // If there is a default translation provider, inject it into the service
        if ($defaultTranslationProvider = Environment::getDefaultTranslationProvider()) {
            ContainerBuilderUtils::injectValuesIntoService(
                TranslationServiceInterface::class,
                'setDefaultProvider',
                $defaultTranslationProvider
            );
        }
    }
}
