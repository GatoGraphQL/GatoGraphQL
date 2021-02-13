<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

class AccessControlRuleBlockRegistry implements AccessControlRuleBlockRegistryInterface
{
    /**
     * @var string[]
     */
    protected array $serviceClasses = [];

    public function addServiceClass(string $serviceClass): void
    {
        $this->serviceClasses[] = $serviceClass;
    }
    /**
     * @return string[]
     */
    public function getServiceClasses(): array
    {
        return $this->serviceClasses;
    }
}
