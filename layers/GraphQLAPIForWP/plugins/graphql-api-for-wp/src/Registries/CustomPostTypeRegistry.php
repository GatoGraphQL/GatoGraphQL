<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;

class CustomPostTypeRegistry implements CustomPostTypeRegistryInterface
{
    /**
     * @var array<string,CustomPostTypeInterface>
     */
    protected array $customPostTypes = [];

    /**
     * Keep the service definition, to unregister the CPTs
     */
    public function addCustomPostType(
        CustomPostTypeInterface $customPostType,
        string $serviceDefinitionID
    ): void {
        $this->customPostTypes[$serviceDefinitionID] = $customPostType;
    }
    /**
     * @return array<string,CustomPostTypeInterface>
     */
    public function getCustomPostTypes(): array
    {
        return $this->customPostTypes;
    }
}
