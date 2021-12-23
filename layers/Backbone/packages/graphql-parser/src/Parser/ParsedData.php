<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PoPBackbone\GraphQLParser\Parser\Ast\OperationInterface;

class ParsedData
{
    public function __construct(
        /** @var OperationInterface[] */
        private array $operations,
        /** @var Fragment[] */
        private array $fragments,
    ) {
    }

    /**
     * @return OperationInterface[]
     */
    public function getOperations(): array
    {
        return $this->operations;
    }

    /**
     * @return Fragment[]
     */
    public function getFragments(): array
    {
        return $this->fragments;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'operations'         => $this->getOperations(),
            'fragments'          => $this->getFragments(),
        ];
    }
}
