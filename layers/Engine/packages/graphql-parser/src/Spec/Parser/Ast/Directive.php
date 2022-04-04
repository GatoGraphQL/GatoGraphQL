<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class Directive extends AbstractAst
{
    use WithArgumentsTrait;

    protected FieldInterface|OperationInterface|Fragment|InlineFragment $parent;

    /**
     * @param Argument[] $arguments
     */
    public function __construct(
        protected readonly $name,
        array $arguments,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setArguments($arguments);
    }

    public function asQueryString(): string
    {
        $strDirectiveArguments = '';
        if ($this->arguments !== []) {
            $strArguments = [];
            foreach ($this->arguments as $argument) {
                $strArguments[] = $argument->asQueryString();
            }
            $strDirectiveArguments = sprintf(
                '(%s)',
                implode(', ', $strArguments)
            );
        }
        return sprintf(
            '@%s%s',
            $this->name,
            $strDirectiveArguments
        );
    }

    public function setParent(FieldInterface|OperationInterface|Fragment|InlineFragment $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): FieldInterface|OperationInterface|Fragment|InlineFragment
    {
        return $this->parent;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
