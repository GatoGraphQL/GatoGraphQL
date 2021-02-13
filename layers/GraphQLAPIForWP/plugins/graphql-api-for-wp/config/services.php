<?php

declare(strict_types=1);

use GraphQLAPI\GraphQLAPI\Registries\AccessControlRuleBlockRegistry;
use GraphQLAPI\GraphQLAPI\Registries\AccessControlRuleBlockRegistryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();
    $services->set(
        AccessControlRuleBlockRegistryInterface::class,
        AccessControlRuleBlockRegistry::class
    );
};
