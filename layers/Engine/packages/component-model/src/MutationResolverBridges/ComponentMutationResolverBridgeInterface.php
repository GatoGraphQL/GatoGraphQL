<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

interface ComponentMutationResolverBridgeInterface
{
    /**
     * @return array<string, mixed>|null
     */
    public function executeMutation(array &$data_properties): ?array;
    public function getMutationResolver(): MutationResolverInterface;
    public function addArgumentsForMutation(\PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $mutationField): void;
}
