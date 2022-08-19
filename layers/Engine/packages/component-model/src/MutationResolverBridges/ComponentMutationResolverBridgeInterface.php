<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

interface ComponentMutationResolverBridgeInterface
{
    /**
     * @return array<string,mixed>|null
     * @param array<string,mixed> $data_properties
     */
    public function executeMutation(array &$data_properties): ?array;
    public function getMutationResolver(): MutationResolverInterface;
    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void;
}
