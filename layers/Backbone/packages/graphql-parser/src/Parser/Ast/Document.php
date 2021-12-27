<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PoPBackbone\GraphQLParser\Parser\Ast\OperationInterface;

class Document
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

    public function getFragment(string $name): ?Fragment
    {
        foreach ($this->fragments as $fragment) {
            if ($fragment->getName() === $name) {
                return $fragment;
            }
        }

        return null;
    }
}
