<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

interface AccessControlRuleBlockRegistryInterface
{
    public function addServiceClass(string $serviceClass): void;
    /**
     * @return string[]
     */
    public function getServiceClasses(): array;
}
