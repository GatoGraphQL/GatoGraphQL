<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\CustomPostTypeInterface;

class CustomPostTypeRegistry implements CustomPostTypeRegistryInterface
{
    /**
     * @var array<string,CustomPostTypeInterface> serviceDefinitionID => CPT
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
     * @return array<string,CustomPostTypeInterface> serviceDefinitionID => CPT
     */
    public function getCustomPostTypes(): array
    {
        return $this->customPostTypes;
    }
}
