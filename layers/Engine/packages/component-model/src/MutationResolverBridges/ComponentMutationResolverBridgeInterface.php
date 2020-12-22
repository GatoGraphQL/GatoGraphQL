<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolverBridges;

interface ComponentMutationResolverBridgeInterface
{
    /**
     * @param array $data_properties
     * @return array<string, mixed>|null
     */
    public function execute(array &$data_properties): ?array;
    public function getMutationResolverClass(): string;
    public function getFormData(): array;
}
