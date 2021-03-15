<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;

class CustomPostTypeRegistry implements CustomPostTypeRegistryInterface
{
    /**
     * @var CustomPostTypeInterface[]
     */
    protected array $customPostTypes = [];

    public function addCustomPostType(CustomPostTypeInterface $customPostType): void
    {
        $this->customPostTypes[] = $customPostType;
    }
    /**
     * @return CustomPostTypeInterface[]
     */
    public function getCustomPostTypes(): array
    {
        return $this->customPostTypes;
    }
}
