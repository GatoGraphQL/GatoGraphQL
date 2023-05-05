<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

trait TestingContainerConfigurationServiceTrait
{
    protected function getPluginRelativePath(): string
    {
        return 'layers/GatoGraphQLForWP/phpunit-plugins/gato-graphql-for-wp-testing';
    }
}
