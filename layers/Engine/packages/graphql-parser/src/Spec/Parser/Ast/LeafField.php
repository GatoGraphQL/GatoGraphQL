<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class LeafField extends AbstractAst implements FieldInterface
{
    use WithArgumentsTrait;
    use WithDirectivesTrait;
    use FieldTrait;

    protected RelationalField|Fragment|InlineFragment|OperationInterface $parent;

    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        protected string $name,
        protected ?string $alias,
        array $arguments,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setArguments($arguments);
        $this->setDirectives($directives);
    }


    /**
     * @todo Temporarily calling ->asQueryString, must work with AST directly!
     * @todo Completely remove this function!!!!
     */
    public function asFieldOutputQueryString(): string
    {
        // Generate the string for arguments
        $strFieldArguments = '';
        if ($this->getArguments() !== []) {
            $strArguments = [];
            foreach ($this->getArguments() as $argument) {
                $strArguments[] = $argument->asQueryString();
            }
            $strFieldArguments = sprintf(
                '(%s)',
                implode(', ', $strArguments)
            );
        }

        /**
         * @todo Temporarily calling ->asQueryString, must work with AST directly!
         */
        $strFieldDirectives = '';
        if ($this->getDirectives() !== []) {
            $directiveQueryString = [];
            foreach ($this->getDirectives() as $directive) {
                // Remove the initial "@"
                $directiveQueryString[] = substr($directive->asQueryString(), 1);
            }
            $strFieldDirectives .= '<' . implode(', ', $directiveQueryString) . '>';
        }

        return sprintf(
            '%s%s%s%s',
            $this->getAlias() !== null ? sprintf('%s: ', $this->getAlias()) : '',
            $this->getName(),
            $strFieldArguments,
            $strFieldDirectives,
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

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }
}
