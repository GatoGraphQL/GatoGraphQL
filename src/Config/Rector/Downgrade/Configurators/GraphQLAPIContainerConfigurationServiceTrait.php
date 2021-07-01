<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

trait GraphQLAPIContainerConfigurationServiceTrait
{
    protected function getPluginRelativePath(): string
    {
        return 'layers/GraphQLAPIForWP/plugins/graphql-api-for-wp';
    }
}
