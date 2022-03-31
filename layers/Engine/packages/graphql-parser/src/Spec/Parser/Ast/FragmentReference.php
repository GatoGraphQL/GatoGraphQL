<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class FragmentReference extends AbstractAst implements FragmentBondInterface
{
    protected RelationalField|Fragment|InlineFragment|OperationInterface $parent;

    public function __construct(
        protected string $name,
        Location $location,
    ) {
        parent::__construct($location);
    }

    public function asQueryString(): string
    {
        return sprintf(
            '...%s',
            $this->name
        );
    }

    public function setParent(RelationalField|Fragment|InlineFragment|OperationInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): RelationalField|Fragment|InlineFragment|OperationInterface
    {
        return $this->parent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
