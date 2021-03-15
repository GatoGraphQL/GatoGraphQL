<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;

interface CustomPostTypeRegistryInterface
{
    public function addCustomPostType(CustomPostTypeInterface $customPostType, string $serviceDefinitionID): void;
    /**
     * @return CustomPostTypeInterface[]
     */
    public function getCustomPostTypes(): array;
}
